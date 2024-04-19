<?php

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
            $table->unsignedBigInteger('contact_person_id');
            $table->unsignedBigInteger('partner_id');
            $table->foreign('contact_person_id')->references('id')->on('contact_people')->cascadeOnDelete();
            $table->foreign('partner_id')->references('id')->on('partners')->cascadeOnDelete();
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
