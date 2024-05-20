<?php

namespace App\Models\Pivots;

use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityPartnerContactPerson extends Pivot
{
    public $table = 'activity_partner_contact_person';
    public $timestamps = false;

    /* Relations */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }

    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
