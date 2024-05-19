<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ContactPerson extends Model
{
    use HasFactory;

    /* Relations */
    public function activities(): belongsToMany
    {
        return $this->belongsToMany(Partner::class)->with('activity');
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class);
    }


    public function projects(): belongsToMany
    {
        return $this->belongsToMany(Partner::class)->with('activity')->with('project');
    }
}
