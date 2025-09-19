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
        Schema::table('vehicules', function (Blueprint $table) {
            $table->dropColumn([
                'statut',
                'type_carburant',
                'nombre_cylindre',
                'nbr_place',
                'prix_achat',
                'prix_location_jour',
                'kilometrage_actuel'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->enum('statut', ['disponible', 'loué', 'maintenance', 'hors_service'])->default('disponible');
            $table->string('type_carburant')->nullable()->default('essence');
            $table->integer('nombre_cylindre')->default(0);
            $table->integer('nbr_place')->default(0);
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->decimal('prix_location_jour', 10, 2)->default(0);
            $table->integer('kilometrage_actuel')->default(0);
        });
    }
};
