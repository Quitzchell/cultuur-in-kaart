<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Neighbourhood extends Model
{
    use HasFactory;

    /* Relations */
    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
