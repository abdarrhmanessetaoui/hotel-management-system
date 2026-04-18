<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Role constants — single source of truth.
     */
    const ROLE_CLIENT     = 'client';
    const ROLE_ADMIN      = 'admin';
    const ROLE_SUPERADMIN = 'superadmin';

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'role',   // client | admin | superadmin
        'is_active',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    /**
     * A client user has many reservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'user_id', 'id');
    }

    /**
     * A hotel admin manages one hotel.
     */
    public function hotel(): HasOne
    {
        return $this->hasOne(Hotel::class, 'admin_id', 'id');
    }

    // ─── Role Helpers ─────────────────────────────────────────────────────────

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Get the user's avatar URL.
     * Returns stored path, external URL, or a fallback UI-Avatars URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->profile_image) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
        }

        if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
            return $this->profile_image;
        }

        return asset('storage/' . $this->profile_image);
    }
}