<?php

namespace App\Models;

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
            ActivityContactPersonPartner::class,
            secondKey: 'id',
            secondLocalKey: 'activity_id'
        );
    }

    public function projects(): HasManyThrough
    {
        return $this->hasManyThrough(Project::class, Activity::class, 'project_id', 'id');
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }
}
