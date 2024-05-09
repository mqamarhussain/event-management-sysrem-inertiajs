<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

class MyEventsController extends Controller
{
    public function index(): Response
    {
        abort_unless(auth()->user()->can('my_events'), 403);
        $attendees = Attendee::query()
            ->with(['event:id,event,event_date,venue_id', 'event.venue:id,venue'])
            ->latest()
            ->where('customer_id', auth()->id())
            ->filter(Request::only('search'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($attendee) {
                return [
                    'id' => $attendee->id,
                    'event' => $attendee->event->event,
                    'venue' => $attendee->event->venue->venue,
                    'event_date' => $attendee->event->event_date->format('d M, Y \a\t H:i:s'),
                    'status' => $attendee->status,
                ];
            });

        return Inertia::render('MyEvents/Index', [
            'filters' => Request::all('search'),
            'attendees' => $attendees,
        ]);
    }
}
