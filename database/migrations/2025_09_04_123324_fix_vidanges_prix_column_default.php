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
        Schema::table('vidanges', function (Blueprint $table) {
            // Make prix column nullable and set default value
            $table->decimal('prix', 8, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vidanges', function (Blueprint $table) {
            // Revert prix column to not nullable without default
            $table->decimal('prix', 8, 2)->nullable(false)->default(null)->change();
        });
    }
};
