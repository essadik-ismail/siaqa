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
        Schema::table('vidanges', function (Blueprint $table) {
            // Rename columns to match form field names
            $table->renameColumn('date', 'date_prevue');
            $table->renameColumn('kilometrage_prochain', 'kilometrage_prochaine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vidanges', function (Blueprint $table) {
            // Reverse the column renames
            $table->renameColumn('date_prevue', 'date');
            $table->renameColumn('kilometrage_prochaine', 'kilometrage_prochain');
        });
    }
};
