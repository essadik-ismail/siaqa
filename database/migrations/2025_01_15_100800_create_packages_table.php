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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->string('license_category')->nullable();
            $table->integer('theory_hours')->default(0);
            $table->integer('practical_hours')->default(0);
            $table->decimal('price', 10, 2);
            $table->integer('validity_days')->default(365); // Package validity period
            $table->boolean('includes_exam')->default(false);
            $table->boolean('includes_materials')->default(false);
            $table->json('features')->nullable(); // Additional features included
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
