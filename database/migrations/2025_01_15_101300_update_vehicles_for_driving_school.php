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
        Schema::table('vehicules', function (Blueprint $table) {
            // Add driving school specific fields
            $table->boolean('is_training_vehicle')->default(false)->after('is_active');
            $table->enum('training_type', ['theory', 'practical', 'both'])->default('practical')->after('is_training_vehicle');
            $table->json('required_licenses')->nullable()->after('training_type'); // Required instructor licenses
            $table->boolean('has_dual_controls')->default(false)->after('required_licenses');
            $table->boolean('has_automatic_transmission')->default(false)->after('has_dual_controls');
            $table->boolean('has_manual_transmission')->default(true)->after('has_automatic_transmission');
            $table->integer('max_students')->default(1)->after('has_manual_transmission');
            $table->decimal('hourly_rate', 8, 2)->default(0)->after('max_students');
            $table->json('safety_features')->nullable()->after('hourly_rate');
            $table->date('last_inspection')->nullable()->after('safety_features');
            $table->date('next_inspection')->nullable()->after('last_inspection');
            $table->boolean('requires_maintenance')->default(false)->after('next_inspection');
            $table->text('maintenance_notes')->nullable()->after('requires_maintenance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            $table->dropColumn([
                'is_training_vehicle',
                'training_type',
                'required_licenses',
                'has_dual_controls',
                'has_automatic_transmission',
                'has_manual_transmission',
                'max_students',
                'hourly_rate',
                'safety_features',
                'last_inspection',
                'next_inspection',
                'requires_maintenance',
                'maintenance_notes'
            ]);
        });
    }
};
