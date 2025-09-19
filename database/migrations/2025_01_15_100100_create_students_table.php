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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('student_number')->unique();
            $table->string('name', 100);
            $table->string('name_ar', 100);
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('cin', 20)->unique();
            $table->date('birth_date');
            $table->string('birth_place', 100);
            $table->text('address');
            $table->string('reference');
            $table->string('cinimage'); //cin image
            $table->string('image');
            $table->string('emergency_contact_name', 100);
            $table->string('emergency_contact_phone', 20);
            $table->string('license_category')->nullable();
            $table->enum('status', ['registered', 'active', 'suspended', 'graduated', 'dropped'])->default('registered');
            $table->date('registration_date');
            $table->integer('theory_hours_completed')->default(0);
            $table->integer('practical_hours_completed')->default(0);
            $table->integer('required_theory_hours')->default(20);
            $table->integer('required_practical_hours')->default(20);
            $table->decimal('total_paid', 10, 2)->default(0);
            $table->decimal('total_due', 10, 2)->default(0);
            $table->json('progress_skills')->nullable(); // Track specific skills learned
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
