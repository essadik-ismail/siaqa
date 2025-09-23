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
            // Modify the status column to include all required values
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue', 'cancelled'])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert to original status values if needed
            $table->enum('status', ['pending', 'partial', 'paid', 'overdue'])->default('pending')->change();
        });
    }
};
