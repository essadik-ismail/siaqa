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
        Schema::create('student_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->date('purchased_date');
            $table->date('expiry_date');
            $table->decimal('price_paid', 10, 2);
            $table->integer('theory_hours_used')->default(0);
            $table->integer('practical_hours_used')->default(0);
            $table->boolean('exam_included')->default(false);
            $table->boolean('exam_taken')->default(false);
            $table->enum('status', ['active', 'expired', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_packages');
    }
};
