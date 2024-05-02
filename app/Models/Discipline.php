<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Discipline extends Model
{
    use HasFactory;

    protected $table = 'disciplines';

    /* Relations */
    public function activities(): BelongsToMany
    {
        return $this->belongsToMany(Activity::class);
    }
}
