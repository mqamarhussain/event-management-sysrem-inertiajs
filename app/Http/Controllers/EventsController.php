<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\UpdateEventRequest;
use Carbon\Carbon;

class EventsController extends Controller
{
    public function index(): Response
    {
        $this->authorize('viewAny', Event::class);

        $events = Event::query()
            ->with('venue')
            ->latest()
            ->filter(Request::only('search'))
            ->when(!auth()->user()->hasRole('Admin'), function ($query) {
                $query->where('promoter_id', auth()->id());
            }, function ($query) {
                $query->where('type', '1');
            })
            ->paginate(10)
            ->withQueryString()
            ->through(function ($event) {
                return [
                    'id' => $event->id,
                    'venue' => $event->venue->venue,
                    'venue_id' => $event->venue_id,
                    'event' => $event->event,
                    'description' => $event->description,
                    'event_date' => $event->event_date->format('d M, Y \a\t H:i:s'),
                    'type' => $event->type,
                    'audience_capacity' => $event->audience_capacity,
                    'payment_type' => $event->payment_type,
                    'amount' => $event->amount,
                    'banner' => $event->banner,
                    'banner_url' => $event->banner_url,
                ];
            });

        return Inertia::render('Events/Index', [
            'filters' => Request::all('search'),
            'events' => $events,
        ]);
    }

    public function show(Event $event): Response
    {
        $this->authorize('view', Event::class);

        $eventData = $event->only([
            'id', 'venue_id', 'venue.venue', 'event', 'description', 'event_date', 'type', 'audience_capacity', 'payment_type',
            'amount', 'banner', 'banner_url'
        ]);
        $venues = Venue::select('id', 'venue')->get();

        return Inertia::render('Events/Show', [
            'event' => $eventData,
            'venues' => $venues
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Event::class);
        $event_date = Request::get('event_date');
        if ($event_date) {
            $event_date = urldecode($event_date);
            $event_date = Carbon::parse($event_date)->toDateTimeLocalString();
        }
        return Inertia::render('Events/Create', [
            'venues' => Venue::query()
                ->get()
                ->map
                ->only('id', 'venue'),
            'event_date' => $event_date
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $this->authorize('create', Event::class);
        $validatedData = $request->validated();
        $validatedData['promoter_id'] = auth()->id();
        $event = Event::create($validatedData);

        if ($request->hasFile('banner')) {
            $event->update(['banner' => $request->file('banner')->store('events/banners', 'public')]);
        }

        return redirect()->route('events.index')->with('success', 'Event created.');
    }

    public function edit(Event $event): Response
    {

        $this->authorize('update', [$event, Event::class]);

        $eventData = $event->only([
            'id', 'venue_id', 'event', 'description', 'event_date', 'type', 'audience_capacity', 'payment_type', 'amount', 'banner', 'banner_url'
        ]);
        $venues = Venue::select('id', 'venue')->get();

        return Inertia::render('Events/Edit', [
            'event' => $eventData,
            'venues' => $venues
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->authorize('update', [$event, Event::class]);

        $validatedData = $request->validated();
        // dd($validatedData);
        unset($validatedData['banner']);
        $event->update($validatedData);
        // dd($event);
        if ($request->hasFile('banner')) {
            $event->update(['banner' => $request->file('banner')->store('events/banners', 'public')]);
        }

        return redirect()->route('events.index')->with('success', 'Event updated.');
    }


    public function destroy(Event $event): RedirectResponse
    {
        $this->authorize('delete', [$event, Event::class]);
        $event->delete();
        return Redirect::route('events.index')->with('success', 'Event deleted.');
    }

    public function restore(Event $event): RedirectResponse
    {
        $event->restore();
        return Redirect::back()->with('success', 'Event restored.');
    }
}
