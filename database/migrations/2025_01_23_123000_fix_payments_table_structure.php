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
        Schema::table('payments', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('payments', 'payment_type')) {
                $table->enum('payment_type', ['lesson', 'exam', 'registration', 'package', 'refund'])->default('lesson')->after('payment_number');
            }
            
            if (!Schema::hasColumn('payments', 'lesson_id')) {
                $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('set null')->after('payment_type');
            }
            
            if (!Schema::hasColumn('payments', 'exam_id')) {
                $table->foreignId('exam_id')->nullable()->constrained()->onDelete('set null')->after('lesson_id');
            }
            
            if (!Schema::hasColumn('payments', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->default(0)->after('amount');
            }
            
            if (!Schema::hasColumn('payments', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0)->after('amount_paid');
            }
            
            if (!Schema::hasColumn('payments', 'due_date')) {
                $table->date('due_date')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('payments', 'paid_date')) {
                $table->date('paid_date')->nullable()->after('due_date');
            }
            
            if (!Schema::hasColumn('payments', 'notes')) {
                $table->text('notes')->nullable()->after('paid_date');
            }
            
            if (!Schema::hasColumn('payments', 'payment_details')) {
                $table->json('payment_details')->nullable()->after('notes');
            }
            
            // Rename existing columns to match validation
            if (Schema::hasColumn('payments', 'payment_date') && !Schema::hasColumn('payments', 'paid_date')) {
                $table->renameColumn('payment_date', 'paid_date');
            }
            
            if (Schema::hasColumn('payments', 'description') && !Schema::hasColumn('payments', 'notes')) {
                $table->renameColumn('description', 'notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Drop added columns
            $columns = ['payment_type', 'lesson_id', 'exam_id', 'amount_paid', 'balance', 'due_date', 'paid_date', 'notes', 'payment_details'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Rename back
            if (Schema::hasColumn('payments', 'paid_date')) {
                $table->renameColumn('paid_date', 'payment_date');
            }
            
            if (Schema::hasColumn('payments', 'notes')) {
                $table->renameColumn('notes', 'description');
            }
        });
    }
};
