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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->enum('report_type', ['revenue', 'student_progress', 'instructor_performance', 'vehicle_usage', 'exam_results', 'custom'])->default('custom');
            $table->json('filters')->nullable(); // Report filters and parameters
            $table->json('data')->nullable(); // Cached report data
            $table->enum('status', ['generating', 'completed', 'failed'])->default('generating');
            $table->string('file_path')->nullable(); // Path to generated report file
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
