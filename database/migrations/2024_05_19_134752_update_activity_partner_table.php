<?php

use App\Models\ContactPerson;
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
        Schema::table('activity_partner', function (Blueprint $table) {
            $table->foreignIdFor(ContactPerson::class)->after('partner_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_partner', function (Blueprint $table) {
            $table->dropForeign('activity_partner_contact_person_foreign');
            $table->dropColumn('contact_person');
        });
    }
};
