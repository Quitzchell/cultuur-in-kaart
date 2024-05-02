<?php

use App\Models\Activity;
use App\Models\Discipline;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_discipline', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Activity::class)->nullable();
            $table->foreignIdFor(Discipline::class)->nullable();
            $table->timestamps();

            $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
            $table->foreign('discipline_id')->references('id')->on('disciplines')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_discipline');
    }
};
