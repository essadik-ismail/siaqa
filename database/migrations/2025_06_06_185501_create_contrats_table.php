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
        Schema::create('contrats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained();
            $table->foreignId('vehicule_id')->constrained();
            $table->foreignId('client_one_id')->constrained('clients');
            $table->string('number_contrat');
            $table->string("numero_document");

            $table->enum("etat_contrat",["en cours", "termine"])->nullable();
            $table->date("date_contrat")->nullable();
            $table->time("heure_contrat")->nullable();
            $table->string("km_depart")->nullable();
            $table->time("heure_depart")->nullable();
            $table->string("lieu_depart")->nullable();
            $table->date("date_retour")->nullable();
            $table->time("heure_retour")->nullable();
            $table->string("lieu_livraison")->nullable();
            $table->integer("nbr_jours")->nullable();
            $table->double("prix")->nullable();
            $table->double("total_ht")->nullable();
            $table->double("total_ttc")->nullable();
            $table->double("remise")->default(0);
            $table->enum("mode_reglement",["cheque","espece","tpe","versement"])->nullable();
            $table->string("caution_assurance")->nullable();
            $table->enum("position_resrvoir",['0','1/4','2/4','3/4','4/4'])->default('0');
            $table->string("prolongation")->nullable();
            // equipment
            $table->boolean("documents")->default(true);
            $table->boolean("cric")->default(true);
            $table->boolean("siege_enfant")->default(false);
            $table->boolean("roue_secours")->default(true);
            $table->boolean("poste_radio")->default(true);
            $table->boolean("plaque_panne")->default(true);
            $table->boolean("gillet")->default(true);
            $table->boolean("extincteur")->default(true);
            // fin equipment
            $table->foreignId('client_two_id')->nullable()->constrained('clients');
            $table->string('autre_fichier')->nullable();
            $table->text("description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrats');
    }
};
