<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    /**
     * Possible reservation statuses.
     */
    const STATUS_PENDING   = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_COMPLETED = 'completed';

    protected $fillable = [
        'hotel_id',
        'room_id',
        'user_id',
        'check_in',
        'check_out',
        'guests',
        'status',
        'notes',
    ];

    protected $appends = [
        'stay_days',
        'total_price',
    ];

    protected $casts = [
        'check_in'  => 'datetime',
        'check_out' => 'datetime',
        'guests'    => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Reservation belongs to a hotel (direct, for easy querying by hotel admin).
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id', 'id');
    }

    /**
     * Reservation belongs to a room.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    /**
     * Reservation belongs to a user (the client who booked).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // ─── Computed Attributes ─────────────────────────────────────────────────

    /**
     * Number of nights between check-in and check-out.
     */
    public function getStayDaysAttribute(): int
    {
        return (int) $this->check_in->diffInDays($this->check_out);
    }

    /**
     * Total price = nights × room price per night.
     * Returns null if room is not loaded to avoid N+1.
     */
    public function getTotalPriceAttribute(): ?float
    {
        if (!$this->relationLoaded('room') || !$this->room) {
            return null;
        }

        return $this->stay_days * (float) $this->room->price;
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}