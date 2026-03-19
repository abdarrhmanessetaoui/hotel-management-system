<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_id',
        'room_number',
        'type',        // e.g. single, double, suite, deluxe
        'price',
        'image',
        'description',
        'status',      // available | unavailable
    ];

    protected $casts = [
        'price'  => 'decimal:2',
        'status' => 'string',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Room belongs to a hotel.
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    /**
     * Room has many reservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'room_id', 'id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Check if the room is available (no active/confirmed reservations
     * overlapping the given date range).
     */
    public function isAvailableFor(string $checkIn, string $checkOut): bool
    {
        return !$this->reservations()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in',  [$checkIn, $checkOut])
                      ->orWhereBetween('check_out', [$checkIn, $checkOut])
                      ->orWhere(function ($q) use ($checkIn, $checkOut) {
                          $q->where('check_in',  '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                      });
            })
            ->exists();
    }
}