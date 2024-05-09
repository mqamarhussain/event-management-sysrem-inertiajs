<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue',
        'address',
        'description',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }
    protected static function newFactory()
    {
        return VenueFactory::new();
    }

    public function scopeOrderByVenue($query)
    {
        $query->orderBy('venue')->orderBy('venue');
    }
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('venue', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        });
    }
}
