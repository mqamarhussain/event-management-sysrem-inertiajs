<?php

namespace App\Models;

use App\Models\Venue;
use App\Models\Audience;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'promoter_id',
        'event',
        'description',
        'event_date',
        'type',
        'audience_capacity',
        'payment_type',
        'amount',
        'banner',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'event_date' => 'datetime',
    ];

    /**
     * Get the venue that owns the event.
     */
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
    protected static function newFactory()
    {
        return EventFactory::new();
    }

    public function promoter()
    {
        return $this->belongsTo(User::class, 'promoter_id', 'id');
    }
    /**
     * Get the audiences for the event.
     */
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
    public function getBannerUrlAttribute()
    {
        // Check if the banner URL starts with 'http'
        if (strpos($this->banner, 'http') === 0) {
            return $this->banner;
        }
        return asset('storage/' . $this->banner);
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereAny(['event', 'description', 'type'], 'like', '%' . $search . '%');
            });
        });
    }
}
