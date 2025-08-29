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
        Schema::create('retour_contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrat_id')->constrained();
            $table->double('km_retour');
            $table->string('kilm_parcoru');
            $table->time('heure_retour');
            $table->date('date_retour');
            $table->enum("position_resrvoir",['0','1/4','2/4','3/4','4/4'])->default('0');
            $table->string('lieu_livraison');
            $table->text('observation');
            $table->enum('etat_regelement' , ['paye','non paye'])->default('paye');
            $table->enum('prolongation' , ['non','oui'])->default('non');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retour_contrats');
    }
};
