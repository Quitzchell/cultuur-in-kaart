<?php

namespace App\Models;

use App\Models\Pivots\ActivityPartnerContactPerson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ContactPerson extends Model
{
    use HasFactory;

    /* Relations */
    public function activities(): HasManyThrough
    {
        return $this->hasManyThrough(
            Activity::class,
            ActivityPartnerContactPerson::class,
            secondKey: 'id',
            secondLocalKey: 'activity_id',
        );
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Activity::class,
            ActivityPartnerContactPerson::class,
            secondKey: 'id',
            secondLocalKey: 'activity_id',
        )->leftJoin('projects', 'projects.id', 'activities.project_id');
    }
}
