<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', User::class);
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => User::query()
                ->with('roles')
                ->filter(Request::only('search', 'trashed'))
                ->latest()
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                    'roles' => $user->roles,
                ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', User::class);
        $roles = Role::get()->map->only(['id', 'name']);
        return Inertia::render('Users/Create', [
            'roles' => $roles
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $data = $request->validated();
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'photo_path' => $request->hasFile('photo') ? $request->file('photo')->store('users') : null,
        ]);
        $roles = Role::find($data['roles']);
        $user->assignRole($roles);
        return redirect()->route('users.index')->with('success', 'User created.');
    }

    public function edit(User $user): Response
    {
        $this->authorize('update', [$user, User::class]);
        $roles = Role::get()->map->only(['id', 'name']);
        return Inertia::render('Users/Edit', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
                'deleted_at' => $user->deleted_at,
                'roles' => $user->roles->map->only('id', 'name')
            ],
            'roles' => $roles
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);
        $data = $request->validated();
        $user->update([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ]);
        if (isset($data['roles'])) {
            $roles = Role::find($data['roles']);
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        if ($request->hasFile('photo')) {
            $user->update(['photo_path' => $request->file('photo')->store('users')]);
        }

        if ($data['password']) {
            $user->update(['password' => $data['password']]);
        }
        if (auth()->user()->can('update_user')) {
            return redirect()->route('users.index')->with('success', 'User updated.');
        }
        return redirect()->route('dashboard')->with('success', 'User updated.');
    }


    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', $user);
        $user->delete();
        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user): RedirectResponse
    {
        $this->authorize('restore', $user);
        $user->restore();
        return Redirect::back()->with('success', 'User restored.');
    }
}
