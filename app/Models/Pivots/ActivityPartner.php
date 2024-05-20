<?php

namespace App\Models\Pivots;

use App\Models\Activity;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityPartner extends Model
{
    protected $table = 'activity_partner';

    public $timestamps = false;

    /* Relations */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
