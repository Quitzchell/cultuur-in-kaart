<?php

use App\Models\Coordinator;
use App\Models\Neighbourhood;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coordinator_neighbourhood', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Coordinator::class);
            $table->foreignIdFor(Neighbourhood::class);
            $table->foreign('coordinator_id')->references('id')->on('coordinators')->cascadeOnDelete();
            $table->foreign('neighbourhood_id')->references('id')->on('neighbourhoods')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordinator_neighbourhood');
    }
};
