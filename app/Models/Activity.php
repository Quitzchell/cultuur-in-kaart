<?php

namespace App\Models;

use App\Models\Pivots\ActivityPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    /* Relations */
    public function activityPartner(): HasMany
    {
        return $this->hasMany(ActivityPartner::class);
    }

    public function contactPeople(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class)->with('contactPeople');
    }

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function neighbourhood(): BelongsTo
    {
        return $this->belongsTo(Neighbourhood::class);
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
