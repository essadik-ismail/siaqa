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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained()->onDelete('set null');
            $table->string('exam_number')->unique();
            $table->enum('exam_type', ['theory', 'practical', 'simulation'])->default('practical');
            $table->string('license_category')->nullable();
            $table->datetime('scheduled_at');
            $table->datetime('completed_at')->nullable();
            $table->integer('duration_minutes')->default(60);
            $table->enum('status', ['scheduled', 'in_progress', 'passed', 'failed', 'cancelled', 'no_show'])->default('scheduled');
            $table->string('location', 200);
            $table->decimal('exam_fee', 8, 2)->default(0);
            $table->integer('score')->nullable(); // Percentage score
            $table->integer('max_score')->default(100);
            $table->json('exam_results')->nullable(); // Detailed results by section
            $table->text('examiner_notes')->nullable();
            $table->text('feedback')->nullable();
            $table->date('retake_date')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
