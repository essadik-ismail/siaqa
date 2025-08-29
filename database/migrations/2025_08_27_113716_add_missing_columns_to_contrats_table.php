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
        Schema::table('contrats', function (Blueprint $table) {
            $table->enum('statut', ['en_cours', 'termine', 'annule'])->default('en_cours')->after('etat_contrat');
            $table->double('montant_total')->default(0)->after('total_ttc');
            $table->date('date_debut')->nullable()->after('date_contrat');
            $table->date('date_fin')->nullable()->after('date_debut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrats', function (Blueprint $table) {
            $table->dropColumn(['statut', 'montant_total', 'date_debut', 'date_fin']);
        });
    }
};
