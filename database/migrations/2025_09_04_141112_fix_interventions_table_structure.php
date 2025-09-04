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
            // Add missing columns
            $table->string('type_intervention')->nullable()->after('vehicule_id');
            $table->date('date_debut')->nullable()->after('type_intervention');
            $table->date('date_fin')->nullable()->after('date_debut');
            $table->string('statut')->default('planifiée')->after('date_fin');
            $table->string('technicien')->nullable()->after('statut');
            $table->unsignedBigInteger('tenant_id')->nullable()->after('technicien');
            
            // Add foreign key constraint for tenant_id
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            // Make prix column nullable with default value
            $table->decimal('prix', 8, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interventions', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn([
                'type_intervention',
                'date_debut', 
                'date_fin',
                'statut',
                'technicien',
                'tenant_id'
            ]);
        });
    }
};