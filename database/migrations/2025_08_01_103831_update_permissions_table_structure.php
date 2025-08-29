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
        Schema::table('permissions', function (Blueprint $table) {
            // Drop existing columns
            $table->dropForeign(['role_id']);
            $table->dropColumn(['service', 'create', 'read', 'update', 'delete', 'role_id']);
            
            // Add new columns
            $table->string('name')->unique()->after('id');
            $table->string('display_name')->after('name');
            $table->text('description')->nullable()->after('display_name');
            $table->string('module')->after('description');
            $table->foreignId('tenant_id')->nullable()->after('module')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permissions', function (Blueprint $table) {
            // Drop new columns
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['name', 'display_name', 'description', 'module', 'tenant_id']);
            
            // Restore original columns
            $table->string('service')->unique();
            $table->boolean('create')->default(false);
            $table->boolean('read')->default(false);
            $table->boolean('update')->default(false);
            $table->boolean('delete')->default(false);
            $table->foreignId('role_id')->nullable()->constrained();
        });
    }
};
