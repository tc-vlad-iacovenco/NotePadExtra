<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;

// Generate annotations based on the User model and its scoped method
/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User unwelcomed()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'welcome_email_sent_at' => 'datetime',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'user_id', 'id');
    }

    public function insights(): HasMany
    {
        return $this->hasMany(Insight::class);
    }

    public function scopeUnwelcomed($query)
    {
        return $query->whereNull('welcome_email_sent_at')
            ->whereNotNull('email_verified_at')
            ->where('email_verified_at', '<=', now()->subMinutes(5));
    }
}
