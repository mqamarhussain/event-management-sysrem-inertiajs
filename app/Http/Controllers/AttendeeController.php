<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Event;
use App\Models\Venue;
use Inertia\Response;
use App\Models\Attendee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as LRequest;

class AttendeeController extends Controller
{
    public function upcomingEvents()
    {
        abort_unless(auth()->user()->can('upcomming_event_list'), 403);
        $myEventsIds = Attendee::where('customer_id', auth()->id())->pluck('event_id');

        $upcomingEvents = Event::query()
            ->with('venue')
            ->where('start_date', '>=', now())
            ->latest()
            ->filter(Request::only('search'))
            ->where('type', '1')
            ->paginate(24)
            ->withQueryString()
            ->through(function ($event) {
                return [
                    'id' => $event->id,
                    'venue' => $event->venue,
                    'venue_id' => $event->venue_id,
                    'event' => $event->event,
                    'description' => $event->description,
                    'event_date' => $event->event_date,
                    'type' => $event->type,
                    'audience_capacity' => $event->audience_capacity,
                    'payment_type' => $event->payment_type,
                    'amount' => $event->amount,
                    'banner' => $event->banner,
                    'banner_url' => $event->banner_url,
                ];
            });
        return Inertia::render(
            'Attendee/UpcomingEvents',
            [
                'upcomingEvents' => $upcomingEvents,
                'myEventsIds' => $myEventsIds,
                'filters' => Request::all('search')
            ]
        );
    }

    public function show(Event $event): Response
    {
        abort_if($event->type == '2', 403);
        $eventData = $event->only([
            'id', 'venue_id', 'venue.venue', 'event', 'description', 'event_date', 'type', 'audience_capacity', 'payment_type', 'amount', 'banner', 'banner'
        ]);
        $venues = Venue::select('id', 'venue')->get();
        $myEventsIds = Attendee::where('customer_id', auth()->id())->pluck('event_id');
        return Inertia::render('Attendee/Show', [
            'event' => $eventData,
            'venues' => $venues,
            'myEventsIds' => $myEventsIds
        ]);
    }

    public function participate(Event $event)
    {
        abort_if($event->type == '2', 403);

        $eventData = $event->only([
            'id', 'amount'
        ]);
        $venues = Venue::select('id', 'venue')->get();

        return Inertia::render('Attendee/Participate', [
            'event' => $eventData,
            'venues' => $venues
        ]);
    }
    public function confirm(LRequest $request)
    {
        $request->validate([
            'event_id' => [
                'required',
                'exists:events,id'
            ],
            'amount_paid' => ['required', 'gte:0']
        ]);
        Attendee::create([
            'event_id' => request('event_id'),
            'amount_paid' => request('amount_paid'),
            'customer_id' => auth()->id(),
        ]);
        return redirect()->route('attendee.upcomingEvents')->with('success', 'Your request has been sent successfully');
    }
}
