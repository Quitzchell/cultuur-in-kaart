<?php

namespace App\Models;

use App\Models\Pivots\ActivityPartnerContactPerson;
use App\Models\Pivots\ActivityPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Activity extends Model
{
    use HasFactory;

    /* Relations */
    public function activityPartner(): HasMany
    {
        return $this->hasMany(ActivityPartner::class);
    }

    public function activityPartnerContactPerson(): HasMany
    {
        return $this->hasMany(ActivityPartnerContactPerson::class);
    }

    public function partnerContactPeople(): HasManyThrough
    {
        return $this->hasManyThrough(Partner::class, ActivityPartner::class)->with('contactPeople');
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

    public function relatedActivities(): HasMany
    {
        return $this->hasMany(self::class, 'project_id', 'project_id')
            ->whereNot('id', $this->getKey());
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
