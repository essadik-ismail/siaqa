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
        Schema::table('payments', function (Blueprint $table) {
            // Drop duplicate columns, keeping the ones that match validation
            if (Schema::hasColumn('payments', 'payment_date')) {
                $table->dropColumn('payment_date');
            }
            
            if (Schema::hasColumn('payments', 'description')) {
                $table->dropColumn('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Add back the dropped columns if needed
            if (!Schema::hasColumn('payments', 'payment_date')) {
                $table->date('payment_date')->nullable();
            }
            
            if (!Schema::hasColumn('payments', 'description')) {
                $table->text('description')->nullable();
            }
        });
    }
};
