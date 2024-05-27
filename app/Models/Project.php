<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Models\Pivots\ActivityPartner;
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

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class, 'primary_coordinator_id');
    }

    public function neighbourhoods(): HasManyThrough
    {
        return $this->hasManyThrough(
            Neighbourhood::class,
            Activity::class,
            'project_id',
            'id',
            'id',
            'neighbourhood_id'
        )->distinct();
    }

    public function partners(): HasManyThrough
    {
        return $this->hasManyThrough(ActivityPartner::class, Activity::class);
    }
}
