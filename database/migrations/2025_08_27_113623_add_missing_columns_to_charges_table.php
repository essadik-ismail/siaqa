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
        Schema::table('charges', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained()->after('id');
            $table->foreignId('vehicule_id')->nullable()->constrained()->after('tenant_id');
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours')->after('montant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropForeign(['vehicule_id']);
            $table->dropColumn(['tenant_id', 'vehicule_id', 'statut']);
        });
    }
};
