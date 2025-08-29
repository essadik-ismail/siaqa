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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('agence_id')->nullable()->constrained();
            $table->foreignId('tenant_id')->nullable()->constrained();
            $table->string('numero_reservation')->unique();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->string('lieu_depart');
            $table->string('lieu_retour');
            $table->integer('nombre_passagers')->default(1);
            $table->json('options')->nullable();
            $table->decimal('prix_total', 10, 2);
            $table->decimal('caution', 10, 2)->default(0);
            $table->enum('statut', ['en_attente', 'confirmee', 'annulee', 'terminee'])->default('en_attente');
            $table->text('notes')->nullable();
            $table->string('motif_annulation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
