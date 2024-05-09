<?php

namespace App\Http\Controllers;

use App\Models\Event; // Assuming your event model is named Event
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        $query = Event::query();
        if ($user->hasRole('Promoter')) {
            $query->where('promoter_id', $user->id);
        } else {
            $query->where('type', '1');
        }

        $events = $query->get();
        $res = [];
        foreach ($events as $event) {
            $res[] = [
                'id' => $event->id,
                'title' => $event->event,
                'start' => $event->event_date->format("Y-m-d H:i:s"),
            ];
        }
        return Inertia::render('Dashboard/Index', [
            'events' => $res,
        ]);
    }
}
