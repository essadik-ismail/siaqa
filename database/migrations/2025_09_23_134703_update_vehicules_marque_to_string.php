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
            // Check if marque_id column exists and drop foreign key constraint if it exists
            if (Schema::hasColumn('vehicules', 'marque_id')) {
                // Try to drop foreign key constraint (ignore if it doesn't exist)
                try {
                    $table->dropForeign(['marque_id']);
                } catch (\Exception $e) {
                    // Foreign key doesn't exist, continue
                }
                
                // Drop the marque_id column
                $table->dropColumn('marque_id');
            }
            
            // Add marque as string column if it doesn't exist
            if (!Schema::hasColumn('vehicules', 'marque')) {
                $table->string('marque')->nullable()->after('tenant_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicules', function (Blueprint $table) {
            // Drop the marque string column
            $table->dropColumn('marque');
            
            // Add back marque_id column
            $table->unsignedBigInteger('marque_id')->nullable()->after('tenant_id');
            
            // Add back foreign key constraint
            $table->foreign('marque_id')->references('id')->on('marques')->onDelete('set null');
        });
    }
};
