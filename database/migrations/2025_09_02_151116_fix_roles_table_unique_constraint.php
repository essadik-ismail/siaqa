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
        Schema::table('roles', function (Blueprint $table) {
            // Drop the existing unique constraint on name
            $table->dropUnique(['name']);
            
            // Add a composite unique constraint on name and tenant_id
            // This allows the same role name for different tenants
            $table->unique(['name', 'tenant_id'], 'roles_name_tenant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('roles_name_tenant_unique');
            
            // Restore the original unique constraint on name only
            $table->unique('name');
        });
    }
};
