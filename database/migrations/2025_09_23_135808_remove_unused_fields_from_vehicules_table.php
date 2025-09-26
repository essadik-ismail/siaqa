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
            // Remove unused fields from vehicules table (only if they exist)
            $columnsToRemove = [
                'landing_display',
                'landing_order',
                'reference',
                'serie',
                'fournisseur',
                'numero_facture',
                'duree_vie',
                'categorie_vehicule',
                'couleur',
                'image',
                'images',
                'kilometrage_location',
                'type_assurance',
                'description',
                'type_carburant',
                'nombre_cylindre',
                'nbr_place',
                'prix_achat',
                'prix_location_jour',
                'kilometrage_actuel',
            ];
            
            // Check each column and drop only if it exists
            foreach ($columnsToRemove as $column) {
                if (Schema::hasColumn('vehicules', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            // Add back the removed fields
            $table->boolean('landing_display')->default(false);
            $table->integer('landing_order')->default(0);
            $table->string('reference')->nullable();
            $table->string('serie')->nullable();
            $table->string('fournisseur')->nullable();
            $table->string('numero_facture')->nullable();
            $table->string('duree_vie')->nullable();
            $table->enum('categorie_vehicule', ["A","B","C","D","E"])->nullable();
            $table->string('couleur')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('kilometrage_location')->nullable();
            $table->string('type_assurance')->nullable();
            $table->text('description')->nullable();
            $table->string('type_carburant')->nullable()->default('essence');
            $table->integer('nombre_cylindre')->default(0);
            $table->integer('nbr_place')->default(0);
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->decimal('prix_location_jour', 10, 2)->default(0);
            $table->integer('kilometrage_actuel')->default(0);
        });
    }
};
