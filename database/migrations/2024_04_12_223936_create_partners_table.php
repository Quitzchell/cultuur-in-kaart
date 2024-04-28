<?php

use App\Models\ContactPerson;
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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('zip');
            $table->string('city');
            $table->foreignIdFor(Neighbourhood::class)->nullable();
            $table->string('street');
            $table->integer('house_number');
            $table->string('house_number_addition')->nullable();
            $table->string('address')->virtualAs("CONCAT(street, ' ', house_number, '', COALESCE(house_number_addition, ''))");
            $table->foreignIdFor(ContactPerson::class)->nullable();
            $table->timestamps();

            $table->foreign('contact_person_id')
                ->references('id')
                ->on('contact_people')
                ->cascadeOnDelete();
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
