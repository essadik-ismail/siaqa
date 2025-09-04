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
        Schema::table('assurances', function (Blueprint $table) {
            // Rename the column from numero_de_police to numero_police
            $table->renameColumn('numero_de_police', 'numero_police');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assurances', function (Blueprint $table) {
            // Rename back from numero_police to numero_de_police
            $table->renameColumn('numero_police', 'numero_de_police');
        });
    }
};
