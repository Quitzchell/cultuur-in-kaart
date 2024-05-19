<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Partner extends Model
{
    use HasFactory;

    /* Relations */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }

    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class, 'primary_contact_person_id');
    }

    public function contactPeople(): BelongsToMany
    {
        return $this->belongsToMany(ContactPerson::class);
    }

    public function neighbourhood(): BelongsTo
    {
        return $this->belongsTo(Neighbourhood::class);
    }

    public function projects(): HasOneThrough
    {
        return $this->hasOneThrough(
            Project::class,
            Activity::class,
            firstKey: 'project_id',
            secondKey: 'id',
            secondLocalKey: 'project_id'
        )->groupBy('project_id');
    }
}
