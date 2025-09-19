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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicule_id')->nullable()->constrained('vehicules')->onDelete('set null');
            $table->string('lesson_number')->unique();
            $table->enum('lesson_type', ['theory', 'practical', 'simulation'])->default('practical');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->datetime('scheduled_at');
            $table->datetime('completed_at')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->string('location', 200)->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->json('skills_covered')->nullable(); // Skills taught in this lesson
            $table->integer('student_rating')->nullable(); // 1-5 rating from student
            $table->text('instructor_notes')->nullable();
            $table->text('student_feedback')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
