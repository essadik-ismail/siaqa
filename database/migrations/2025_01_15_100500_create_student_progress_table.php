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
        Schema::create('student_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->string('skill_category', 100); // e.g., 'parking', 'highway_driving', 'city_driving'
            $table->string('skill_name', 200); // e.g., 'parallel_parking', 'lane_changing'
            $table->enum('skill_level', ['beginner', 'intermediate', 'advanced', 'mastered'])->default('beginner');
            $table->integer('hours_practiced')->default(0);
            $table->integer('attempts')->default(0);
            $table->integer('success_rate')->default(0); // Percentage
            $table->text('instructor_notes')->nullable();
            $table->json('assessment_criteria')->nullable(); // Specific criteria for this skill
            $table->boolean('is_required')->default(true);
            $table->boolean('is_completed')->default(false);
            $table->date('last_practiced')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_progress');
    }
};
