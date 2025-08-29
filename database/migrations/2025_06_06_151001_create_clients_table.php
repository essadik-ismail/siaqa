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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained();
            $table->enum('type', ['client', 'societe'])->default('client');
            $table->string('nom');
            $table->string('prenom')->nullable();
            $table->string('ice_societe')->nullable();
            $table->string('nom_societe')->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_de_naissance')->nullable();
            $table->string('adresse')->nullable();
            $table->string('telephone')->nullable();
            $table->string('ville')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('code_postal')->nullable(); // Alternative name
            $table->string('pays')->nullable();
            $table->string('email')->nullable();
            $table->string('nationalite')->nullable();
            $table->string('numero_cin')->nullable();
            $table->date('date_cin_expiration')->nullable();
            $table->string('numero_permis')->nullable();
            $table->date('date_permis')->nullable();
            $table->date('date_obtention_permis')->nullable();
            $table->string('passport')->nullable();
            $table->date('date_passport')->nullable();
            $table->string('numero_piece_identite')->nullable();
            $table->string('type_piece_identite')->nullable();
            $table->date('date_expiration_piece')->nullable();
            $table->string('profession')->nullable();
            $table->string('employeur')->nullable();
            $table->decimal('revenu_mensuel', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('bloquer')->default(false);
            $table->boolean('is_blacklisted')->default(false);
            $table->boolean('is_blacklist')->default(false);
            $table->string('motif_blacklist')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
