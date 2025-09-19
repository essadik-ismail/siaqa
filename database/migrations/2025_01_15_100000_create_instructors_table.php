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
        Schema::create('instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_number')->unique();
            $table->string('license_number')->unique();
            $table->date('license_expiry');
            $table->string('license_categories')->nullable();
            $table->integer('years_experience')->default(0);
            $table->decimal('hourly_rate', 8, 2)->default(0);
            $table->integer('max_students')->default(20);
            $table->integer('current_students')->default(0);
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->json('availability_schedule')->nullable(); // Weekly availability
            $table->json('specializations')->nullable(); // Special skills/certifications
            $table->text('notes')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
