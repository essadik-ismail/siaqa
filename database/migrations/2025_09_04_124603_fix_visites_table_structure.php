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
        Schema::table('visites', function (Blueprint $table) {
            // Rename existing columns to match model expectations
            $table->renameColumn('date', 'date_visite');
            $table->renameColumn('kilometrage_prochain', 'prochaine_visite');
            
            // Add missing columns that the model expects
            $table->string('type_visite')->nullable()->after('date_visite');
            $table->string('resultat')->nullable()->after('type_visite');
            $table->text('observations')->nullable()->after('resultat');
            $table->string('statut')->default('en_attente')->after('observations');
            $table->unsignedBigInteger('tenant_id')->nullable()->after('statut');
            
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
        Schema::table('visites', function (Blueprint $table) {
            // Drop foreign key constraint first
            $table->dropForeign(['tenant_id']);
            
            // Drop the added columns
            $table->dropColumn([
                'type_visite',
                'resultat',
                'observations',
                'statut',
                'tenant_id'
            ]);
            
            // Revert column renames
            $table->renameColumn('date_visite', 'date');
            $table->renameColumn('prochaine_visite', 'kilometrage_prochain');
            
            // Revert prix column
            $table->decimal('prix', 8, 2)->nullable(false)->default(null)->change();
        });
    }
};
