<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Role;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreRoleRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Role::class);
        $roles = Role::query()
            ->with('permissions')
            ->latest()
            ->filter(Request::only('search'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'permissions' => $role->permissions,
                ];
            });

        return Inertia::render('Roles/Index', [
            'filters' => Request::all('search'),
            'roles' => $roles,
        ]);
    }

    public function show(Role $role): Response
    {
        $this->authorize('view', $role);

        $roleData = $role->only([
            'id',
            'name',
            'permissions'
        ]);
        return Inertia::render('Roles/Show', [
            'role' => $roleData,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Role::class);
        return Inertia::render('Roles/Create', [
            'permissions' => Permission::query()
                ->get()
                ->map
                ->only('id', 'name')
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);
        $name = $request->validated('name');
        $permissions = $request->validated('permissions', []);
        $role = Role::create(['name' => $name]);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role created.');
    }

    public function edit(Role $role): Response
    {
        $this->authorize('update', $role);

        $roleData = [
            'id' => $role->id,
            'name' => $role->name,
            'permissions' => $role->permissions->map->only('id', 'name'),
        ];

        return Inertia::render('Roles/Edit', [
            'role' => $roleData,
            'permissions' => Permission::query()
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }


    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $this->authorize('update', $role);
        $validatedData = $request->validated();
        $name = $validatedData['name'];
        $permissions = $validatedData['permissions'];
        $role->update(['name' => $name]);
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->with('success', 'Role updated.');
    }


    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize('delete', $role);

        if ($role->id < 4) {
            return Redirect::back()->with('error', 'First three roles can\'t be deleted');
        }
        $role->delete();
        return Redirect::route('roles.index')->with('success', 'Role deleted.');
    }
    public function deletMultiple($roleIds)
    {
        foreach ($roleIds as $role) {
            $this->authorize('delete', $role);
        }
        $roleIds = explode(',', $roleIds);
        $firstThreeRoleIds = Role::orderBy('id')->take(3)->pluck('id')->toArray();
        $roleIds = array_diff($roleIds, $firstThreeRoleIds);
        Role::whereIn('id', $roleIds)->delete();
        return redirect()->route('roles.index')->with('success', 'Roles deleted successfully. First three roles can\'t be deleted');
    }
}
