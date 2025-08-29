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
        Schema::create('agences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained();
            $table->string('logo')->nullable();
            $table->string('nom_agence')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ville')->nullable();
            $table->string('rc')->nullable();
            $table->string('patente')->nullable();
            $table->string('IF')->nullable();
            $table->string('n_cnss')->nullable();
            $table->string('ICE')->nullable();
            $table->string('n_compte_bancaire')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agences');
    }
};
