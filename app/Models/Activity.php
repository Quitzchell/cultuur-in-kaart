<?php

namespace App\Models;

use App\Models\Pivots\ActivityPartnerContactPerson;
use App\Models\Pivots\ActivityPartner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::saved(static function (Activity $activity) {
            $partnerIds = $activity->activityPartnerContactPerson->pluck('partner_id')->unique();
            foreach ($activity->activityPartner as $record) {
                if (!$partnerIds->contains($record->partner_id)) {
                    $activity->activityPartner()->delete($record->getKey());
                }
            }
        });
    }

    /* Relations */
    public function activityPartner(): HasMany
    {
        return $this->hasMany(ActivityPartner::class);
    }

    public function activityPartnerContactPerson(): HasMany
    {
        return $this->hasMany(ActivityPartnerContactPerson::class);
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
