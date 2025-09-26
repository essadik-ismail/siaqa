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
        if (!Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('created_by');
                $table->string('name', 200);
                $table->text('description')->nullable();
                $table->string('report_type', 50)->default('custom');
                $table->json('filters')->nullable();
                $table->json('data')->nullable();
                $table->string('status', 20)->default('generating');
                $table->string('file_path')->nullable();
                $table->timestamp('generated_at')->nullable();
                $table->timestamps();
                
                $table->index('tenant_id');
                $table->index('created_by');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
