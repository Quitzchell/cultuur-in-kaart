<?php

use App\Models\Discipline;
use App\Models\Neighbourhood;
use App\Models\Project;
use App\Models\Task;
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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->text('comment')->nullable();
            $table->foreignIdFor(Task::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Project::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Neighbourhood::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Discipline::class)->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
