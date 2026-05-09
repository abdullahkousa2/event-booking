<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'event_date',
        'price',
        'total_seats',
        'available_seats',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'event_date'      => 'datetime',
            'price'           => 'decimal:2',
            'total_seats'     => 'integer',
            'available_seats' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('available_seats', '>', 0);
    }

    public function hasAvailableSeats(int $requested = 1): bool
    {
        return $this->available_seats >= $requested;
    }
}
