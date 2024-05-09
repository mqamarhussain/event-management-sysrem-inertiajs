<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'event_id',
        'content',
        'amount_paid',
        'payment_status',
        'status',
    ];

    /**
     * Get the event that the audience belongs to.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereAny(['event.event', 'venue.venue', 'status'], 'like', '%' . $search . '%');
            });
        });
    }
}
