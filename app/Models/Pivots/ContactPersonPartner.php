<?php

namespace App\Models\Pivots;

use App\Models\ContactPerson;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ContactPersonPartner extends Pivot
{
    protected $table = 'contact_person_partner';
    public $timestamps = false;

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function contactPerson(): BelongsTo
    {
        return $this->belongsTo(ContactPerson::class);
    }
}
