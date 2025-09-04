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
            // Add missing columns that the model expects
            $table->string('type_huile')->nullable()->after('kilometrage_prochain');
            $table->decimal('quantite_huile', 8, 2)->nullable()->after('type_huile');
            $table->string('filtre_huile')->nullable()->after('quantite_huile');
            $table->string('filtre_air')->nullable()->after('filtre_huile');
            $table->string('filtre_carburant')->nullable()->after('filtre_air');
            $table->decimal('cout_estime', 8, 2)->nullable()->after('filtre_carburant');
            $table->string('statut')->default('planifiee')->after('cout_estime');
            $table->text('notes')->nullable()->after('statut');
            $table->unsignedBigInteger('tenant_id')->nullable()->after('notes');
            
            // Add foreign key constraint for tenant_id
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vidanges', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['tenant_id']);
            
            // Drop the added columns
            $table->dropColumn([
                'type_huile',
                'quantite_huile', 
                'filtre_huile',
                'filtre_air',
                'filtre_carburant',
                'cout_estime',
                'statut',
                'notes',
                'tenant_id'
            ]);
        });
    }
};
