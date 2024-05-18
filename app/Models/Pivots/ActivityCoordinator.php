<?php

namespace App\Models\Pivots;

use App\Models\Activity;
use App\Models\Coordinator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityCoordinator extends Pivot
{
    /* Relations */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(Coordinator::class);
    }
}
