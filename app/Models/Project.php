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

    public function contactPeople(): HasMany
    {
        return$this->hasMany(Activity::class)->with('partners')->with('contactPeople');
    }

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class, 'primary_coordinator_id');
    }

    public function neighbourhoods(): HasMany
    {
        return $this->hasMany(Activity::class)->with('neighbourhood');
    }

    public function partners(): HasManyThrough
    {
        return $this->hasManyThrough(ActivityPartner::class, Activity::class);
    }
}
