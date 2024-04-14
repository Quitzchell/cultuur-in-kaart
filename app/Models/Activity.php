<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    use HasFactory;

    /* Relations */
    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }

    public function contactPeople(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }

    public function project(): HasOne
    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }

    {
        return $this->belongsTo(Project::class);
    }
}
