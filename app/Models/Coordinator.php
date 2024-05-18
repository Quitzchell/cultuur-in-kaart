<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Coordinator extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'workdays' => 'array'
        ];
    }

    /* Relations */
    public function neighbourhoods(): BelongsToMany
    {
        return $this->belongsToMany(Neighbourhood::class);
    }

    /* Permissions */
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@soc.nl') && $this->hasVerifiedEmail();
    }
}
