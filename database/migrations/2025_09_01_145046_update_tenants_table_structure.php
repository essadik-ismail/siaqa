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
        Schema::table('tenants', function (Blueprint $table) {
            // Add new fields if they don't exist
            if (!Schema::hasColumn('tenants', 'company_name')) {
                $table->string('company_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('tenants', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('company_name');
            }
            if (!Schema::hasColumn('tenants', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
            if (!Schema::hasColumn('tenants', 'address')) {
                $table->text('address')->nullable()->after('contact_phone');
            }
            if (!Schema::hasColumn('tenants', 'notes')) {
                $table->text('notes')->nullable()->after('address');
            }
            if (!Schema::hasColumn('tenants', 'max_users')) {
                $table->integer('max_users')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('tenants', 'max_vehicles')) {
                $table->integer('max_vehicles')->nullable()->after('max_users');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Revert new fields
            $table->dropColumn([
                'company_name',
                'contact_email',
                'contact_phone',
                'address',
                'notes',
                'max_users',
                'max_vehicles'
            ]);
        });
    }
};