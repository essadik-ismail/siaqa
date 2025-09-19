<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'payment_number',
        'payment_type',
        'lesson_id',
        'exam_id',
        'amount',
        'amount_paid',
        'balance',
        'payment_method',
        'status',
        'due_date',
        'paid_date',
        'transaction_id',
        'notes',
        'payment_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
        'payment_details' => 'array',
    ];

    /**
     * Get the tenant that owns the payment.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student for the payment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the lesson for the payment.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the exam for the payment.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid payments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include partial payments.
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }

    /**
     * Scope a query to only include overdue payments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope a query to only include cancelled payments.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include lesson payments.
     */
    public function scopeLesson($query)
    {
        return $query->where('payment_type', 'lesson');
    }

    /**
     * Scope a query to only include exam payments.
     */
    public function scopeExam($query)
    {
        return $query->where('payment_type', 'exam');
    }

    /**
     * Scope a query to only include registration payments.
     */
    public function scopeRegistration($query)
    {
        return $query->where('payment_type', 'registration');
    }

    /**
     * Scope a query to only include package payments.
     */
    public function scopePackage($query)
    {
        return $query->where('payment_type', 'package');
    }

    /**
     * Scope a query to only include refund payments.
     */
    public function scopeRefund($query)
    {
        return $query->where('payment_type', 'refund');
    }

    /**
     * Scope a query to only include payments for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('created_at', $date);
    }

    /**
     * Scope a query to only include payments for a specific date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if payment is partial.
     */
    public function isPartial(): bool
    {
        return $this->status === 'partial';
    }

    /**
     * Check if payment is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    /**
     * Check if payment is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if payment is for a lesson.
     */
    public function isForLesson(): bool
    {
        return $this->payment_type === 'lesson';
    }

    /**
     * Check if payment is for an exam.
     */
    public function isForExam(): bool
    {
        return $this->payment_type === 'exam';
    }

    /**
     * Check if payment is for registration.
     */
    public function isForRegistration(): bool
    {
        return $this->payment_type === 'registration';
    }

    /**
     * Check if payment is for a package.
     */
    public function isForPackage(): bool
    {
        return $this->payment_type === 'package';
    }

    /**
     * Check if payment is a refund.
     */
    public function isRefund(): bool
    {
        return $this->payment_type === 'refund';
    }

    /**
     * Get the remaining balance.
     */
    public function getRemainingBalanceAttribute(): float
    {
        return $this->amount - $this->amount_paid;
    }

    /**
     * Get the payment percentage.
     */
    public function getPaymentPercentageAttribute(): float
    {
        if ($this->amount == 0) return 0;
        return ($this->amount_paid / $this->amount) * 100;
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 2) . ' MAD';
    }

    /**
     * Get the formatted amount paid.
     */
    public function getFormattedAmountPaidAttribute(): string
    {
        return number_format($this->amount_paid, 2) . ' MAD';
    }

    /**
     * Get the formatted balance.
     */
    public function getFormattedBalanceAttribute(): string
    {
        return number_format($this->balance, 2) . ' MAD';
    }

    /**
     * Get the payment method label.
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        $methods = [
            'cash' => 'Cash',
            'card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer',
            'check' => 'Check',
            'online' => 'Online Payment',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    /**
     * Get the payment type label.
     */
    public function getPaymentTypeLabelAttribute(): string
    {
        $types = [
            'lesson' => 'Lesson Payment',
            'exam' => 'Exam Payment',
            'registration' => 'Registration Fee',
            'package' => 'Package Payment',
            'refund' => 'Refund',
        ];

        return $types[$this->payment_type] ?? $this->payment_type;
    }

    /**
     * Process a payment.
     */
    public function processPayment(float $amount, string $method = null, string $transactionId = null): void
    {
        $newAmountPaid = $this->amount_paid + $amount;
        $newBalance = $this->amount - $newAmountPaid;

        $updateData = [
            'amount_paid' => $newAmountPaid,
            'balance' => $newBalance,
        ];

        if ($method) {
            $updateData['payment_method'] = $method;
        }

        if ($transactionId) {
            $updateData['transaction_id'] = $transactionId;
        }

        if ($newBalance <= 0) {
            $updateData['status'] = 'paid';
            $updateData['paid_date'] = now();
        } elseif ($newAmountPaid > 0) {
            $updateData['status'] = 'partial';
        }

        $this->update($updateData);
    }

    /**
     * Mark payment as overdue.
     */
    public function markAsOverdue(): void
    {
        if ($this->isPending() && $this->due_date && $this->due_date < now()) {
            $this->update(['status' => 'overdue']);
        }
    }

    /**
     * Cancel payment.
     */
    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? ($this->notes . "\nCancelled: " . $reason) : $this->notes,
        ]);
    }

    /**
     * Check if payment is due soon (within 7 days).
     */
    public function isDueSoon(): bool
    {
        return $this->isPending() && 
               $this->due_date && 
               $this->due_date <= now()->addDays(7) && 
               $this->due_date >= now();
    }

    /**
     * Get the days until due.
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->due_date) return null;
        
        $diff = now()->diffInDays($this->due_date, false);
        return $diff >= 0 ? $diff : null;
    }
}
