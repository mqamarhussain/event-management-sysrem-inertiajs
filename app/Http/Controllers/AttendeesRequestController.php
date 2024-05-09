<?php

namespace App\Http\Controllers;

use Inertia\Response;
use App\Models\Attendee;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class AttendeesRequestController extends Controller
{
    public function index(): Response
    {

        $attendees = Attendee::query()
            ->with('customer')
            ->withWhereHas('event', function ($query) {
                $query->when(!auth()->user()->hasRole('Admin'), function ($q) {
                    $q->where('promoter_id', auth()->id());
                });
            })
            ->latest()
            ->filter(Request::only('search'))
            ->paginate(10)
            ->withQueryString()
            ->through(function ($attendee) {
                return [
                    'id' => $attendee->id,
                    'customer' => $attendee->customer,
                    'event' => $attendee->event,
                    'content' => $attendee->content,
                    'amount_paid' => $attendee->amount_paid,
                    'payment_status' => $attendee->payment_status,
                    'status' => $attendee->status,
                    'status_text' => $attendee->status_text,
                ];
            });
        return Inertia::render('Attendees/Index', [
            'filters' => Request::all('search'),
            'attendees' => $attendees,
        ]);
    }

    public function accept(Attendee $attendee)
    {
        abort_unless(auth()->user()->can('accept_attendee_requests'), 403);
        $attendee->update([
            'status' => '1'
        ]);
        return redirect()->route('attendees.requests')->with('success', 'Event created.');
    }
}
