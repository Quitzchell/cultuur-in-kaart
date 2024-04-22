<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityPartner extends Pivot
{
    public function partner(): BelongsTo
    {
        return $this->belongsTo(partner::class);
    }
}
