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
        Schema::create('analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('metric_name', 100); // revenue, student_count, lesson_count, etc.
            $table->decimal('metric_value', 15, 2)->default(0);
            $table->json('dimensions')->nullable(); // Additional dimensions like instructor_id, vehicle_id, etc.
            $table->timestamps();
            
            $table->index(['tenant_id', 'date', 'metric_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics');
    }
};
