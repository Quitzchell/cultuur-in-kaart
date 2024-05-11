<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Partner extends Model
{
    use HasFactory;

    /* Relations */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }

    public function contactPeople(): BelongsToMany
    {
        return $this->belongsToMany(ContactPerson::class);
    }

    public function primaryContactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class);
    }

    public function neighbourhood(): BelongsTo
    {
        return $this->belongsTo(Neighbourhood::class);
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(
            Project::class,
            Activity::class,
            'activity_partner.partner_id',
            'id',
            'id',
            'project_id'
        )->join(
            'activity_partner',
            'activities.id',
            'activity_partner.activity_id'
        )->distinct();
    }
}
