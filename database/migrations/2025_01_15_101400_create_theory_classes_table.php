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
        Schema::create('theory_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('instructor_id')->constrained()->onDelete('cascade');
            $table->string('class_number')->unique();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('license_category', ['A', 'B', 'C', 'D', 'E'])->default('B');
            $table->datetime('scheduled_at');
            $table->datetime('completed_at')->nullable();
            $table->integer('duration_minutes')->default(120);
            $table->integer('max_students')->default(20);
            $table->integer('current_students')->default(0);
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->string('classroom', 100)->nullable();
            $table->decimal('price_per_student', 8, 2)->default(0);
            $table->json('topics_covered')->nullable(); // Topics covered in this class
            $table->json('materials')->nullable(); // Required materials
            $table->text('instructor_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theory_classes');
    }
};
