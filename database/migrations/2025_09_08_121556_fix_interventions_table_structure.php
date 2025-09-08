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
        Schema::table('interventions', function (Blueprint $table) {
            // Make date column nullable to fix the default value error
            $table->date('date')->nullable()->change();
            
            // Add missing columns from the form
            $table->string('priorite')->nullable()->after('statut');
            $table->integer('kilometrage_intervention')->nullable()->after('technicien');
            $table->decimal('duree_estimee', 8, 2)->nullable()->after('kilometrage_intervention');
            $table->text('pieces_utilisees')->nullable()->after('duree_estimee');
            $table->text('notes')->nullable()->after('pieces_utilisees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropColumn(['priorite', 'kilometrage_intervention', 'duree_estimee', 'pieces_utilisees', 'notes']);
        });
    }
};
