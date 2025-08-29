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
        Schema::create('assurances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->constrained();
            $table->string('numero_assurance');
            $table->string('numero_de_police');
            $table->date('date');
            $table->date('date_prochaine');
            $table->date('date_reglement');
            $table->string('periode')->nullable();
            $table->double('prix');
            $table->json('fichiers')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assurances');
    }
};
