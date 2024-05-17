<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Project extends Model
{
    use HasFactory;

    /* Casts */

    protected $casts = [
        'budget_spend' => MoneyCast::class,
    ];

    /* Relations */

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function coordinator(): belongsTo
    {
        return $this->belongsTo(Coordinator::class, 'primary_coordinator_id');
    }

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function partners(): HasManyThrough
    {
        return $this->hasManyThrough(ActivityPartner::class, Activity::class);
    }

    public function neighbourhoods(): BelongsToMany
    {
        return $this->belongsToMany(Neighbourhood::class, Activity::class);
    }
}
