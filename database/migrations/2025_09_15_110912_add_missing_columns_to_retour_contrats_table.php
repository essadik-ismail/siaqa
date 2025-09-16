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
        Schema::table('retour_contrats', function (Blueprint $table) {
            $table->integer('kilometrage_retour')->nullable()->after('km_retour');
            $table->enum('niveau_carburant', ['vide', '1/4', '1/2', '3/4', 'plein'])->nullable()->after('position_resrvoir');
            $table->enum('etat_vehicule', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable()->after('niveau_carburant');
            $table->decimal('frais_supplementaires', 10, 2)->default(0)->after('etat_vehicule');
            $table->foreignId('tenant_id')->nullable()->after('frais_supplementaires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('retour_contrats', function (Blueprint $table) {
            $table->dropColumn(['kilometrage_retour', 'niveau_carburant', 'etat_vehicule', 'frais_supplementaires', 'tenant_id']);
        });
    }
};
