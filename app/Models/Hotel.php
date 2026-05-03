<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'description',
        'location',
        'image',
        'rating',
        'admin_id',   // The user (role=admin) who manages this hotel
    ];

    protected $casts = [
        'rating' => 'decimal:1',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * Hotel belongs to a city.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    /**
     * Hotel has many rooms.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'hotel_id', 'id');
    }

    /**
     * Hotel has many dynamic room types.
     */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class, 'hotel_id', 'id');
    }

    /**
     * Hotel has many reservations (via rooms or directly).
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'hotel_id', 'id');
    }

    /**
     * The admin user assigned to manage this hotel.
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Average rating rounded to 1 decimal — useful in views.
     */
    public function getFormattedRatingAttribute(): string
    {
        return number_format($this->rating, 1);
    }
}
