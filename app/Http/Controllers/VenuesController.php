<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class VenuesController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Venue::class);

        return Inertia::render('Venues/Index', [
            'filters' => Request::all('search'),
            'venues' => Venue::query()
                ->orderByVenue()
                ->filter(Request::only('search'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($venue) => [
                    'id' => $venue->id,
                    'venue' => $venue->venue,
                    'description' => $venue->description,
                    'address' => $venue->address,
                ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Venue::class);

        return Inertia::render('Venues/Create');
    }

    public function store(): RedirectResponse
    {
        $this->authorize('create', Venue::class);

        Request::validate([
            'venue' => ['required', 'max:50'],
            'address' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        Venue::create([
            'venue' => Request::get('venue'),
            'address' => Request::get('address'),
            'description' => Request::get('description'),
        ]);

        return Redirect::route('venues.index')->with('success', 'Venue created.');
    }

    public function edit(Venue $venue): Response
    {
        $this->authorize('update', $venue);

        return Inertia::render('Venues/Edit', [
            'venue' => [
                'id' => $venue->id,
                'venue' => $venue->venue,
                'address' => $venue->address,
                'description' => $venue->description,
            ],
        ]);
    }

    public function update(Venue $venue): RedirectResponse
    {
        $this->authorize('update', $venue);

        Request::validate([
            'venue' => ['required', 'max:50'],
            'address' => ['required', 'max:50'],
            'description' => ['required'],
        ]);

        $venue->update(Request::only('venue', 'address', 'description'));

        return Redirect::back()->with('success', 'Venue updated.');
    }

    public function destroy(Venue $venue): RedirectResponse
    {
        $this->authorize('delete', $venue);
        $venue->delete();
        return Redirect::back()->with('success', 'Venue deleted.');
    }
}
