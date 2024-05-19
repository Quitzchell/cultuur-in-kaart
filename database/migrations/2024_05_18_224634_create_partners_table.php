<?php

use App\Models\Neighbourhood;
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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street');
            $table->string('house_number');
            $table->string('city');
            $table->foreignIdFor(Neighbourhood::class)->nullable()->constrained();
            $table->string('zip');
            $table->string('house_number_addition')->nullable();
            $table->string('address')->virtualAs("CONCAT(street, ' ', house_number, '', COALESCE(house_number_addition, ''))");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
