<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Activity extends Model
{
    use HasFactory;

    /* Relations */
    public function tasks(): HasOne
    {
        return $this->hasOne(Task::class);
    }

    public function contactPeople(): HasMany
    {
        return $this->hasMany(ContactPerson::class);
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }
}
