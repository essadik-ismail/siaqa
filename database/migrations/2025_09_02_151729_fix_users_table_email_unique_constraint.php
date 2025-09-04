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
        Schema::table('users', function (Blueprint $table) {
            // Drop the existing unique constraint on email
            $table->dropUnique(['email']);
            
            // Add a composite unique constraint on email and tenant_id
            // This allows the same email for different tenants
            $table->unique(['email', 'tenant_id'], 'users_email_tenant_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the composite unique constraint
            $table->dropUnique('users_email_tenant_unique');
            
            // Restore the original unique constraint on email only
            $table->unique('email');
        });
    }
};
