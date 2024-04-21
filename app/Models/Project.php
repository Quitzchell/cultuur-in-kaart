<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    /* Casts */

    protected $casts = [
        'budget_spend' => MoneyCast::class,
    ];

    /* Relations */

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function primaryCoordinator(): belongsTo
    {
        return $this->belongsTo(Coordinator::class, 'primary_coordinator_id');
    }

    public function coordinators(): BelongsToMany
    {
        return $this->belongsToMany(Coordinator::class);
    }

    public function partners(): belongsToMany
    {
        return $this->belongsToMany(Project::class, 'activity_partner', 'partner_id', 'activity_id')
            ->leftJoin('activities', 'activity_partner.activity_id', '=', 'activities.id')
            ->where('activities.project_id', $this->getKey());
    }
}
