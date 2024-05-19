<?php

use App\Models\ContactPerson;
use App\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contact_person_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ContactPerson::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Partner::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_person_partner');
    }
};
