<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Neighbourhood extends Model
{
    use HasFactory;

    /* Relations */
    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }
}
