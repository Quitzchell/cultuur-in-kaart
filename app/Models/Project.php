<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    /* Relations */
    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class, 'primary_coordinator_id');
    }
}
