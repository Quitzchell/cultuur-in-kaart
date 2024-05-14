<?php

use App\Models\Activity;
use App\Models\ContactPerson;
use App\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_contact_person_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Activity::class);
            $table->foreignIdFor(ContactPerson::class);
            $table->foreignIdFor(Partner::class);
            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
            $table->foreign('contact_person_id')->references('id')->on('contact_people')->cascadeOnDelete();
            $table->foreign('partner_id')->references('id')->on('partners')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_contact_person_partner');
    }
};
