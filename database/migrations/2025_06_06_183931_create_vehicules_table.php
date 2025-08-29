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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained();
            $table->foreignId('agence_id')->nullable()->constrained();
            $table->foreignId('marque_id')->constrained();
            $table->string('name');
            $table->string('immatriculation')->unique();
            $table->enum('statut', ['disponible', 'en_location', 'en_maintenance', 'hors_service'])->default('disponible');
            $table->boolean('is_active')->default(true);
            $table->string('type_carburant')->nullable()->default('essence');
            $table->integer('nombre_cylindre')->default(0);
            $table->integer('nbr_place')->default(0);
            $table->string('reference')->nullable();
            $table->string('serie')->nullable();
            $table->string('fournisseur')->nullable();
            $table->string('numero_facture')->nullable();
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->decimal('prix_location_jour', 10, 2)->default(0);
            $table->string('duree_vie')->nullable();
            $table->integer('kilometrage_actuel')->default(0);
            $table->enum('categorie_vehicule', ["A","B","C","D","E"])->nullable();
            $table->string('couleur')->nullable();
            $table->string('image')->nullable();
            $table->json('images')->nullable();
            $table->string('kilometrage_location')->nullable();
            $table->string('type_assurance')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
