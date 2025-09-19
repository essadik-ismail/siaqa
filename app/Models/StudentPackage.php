<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPackage extends Model
{
    use HasFactory;

    protected $table = 'student_packages';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'package_id',
        'purchased_date',
        'expiry_date',
        'price_paid',
        'theory_hours_used',
        'practical_hours_used',
        'exam_included',
        'exam_taken',
        'status',
        'notes',
    ];

    protected $casts = [
        'purchased_date' => 'date',
        'expiry_date' => 'date',
        'price_paid' => 'decimal:2',
        'theory_hours_used' => 'integer',
        'practical_hours_used' => 'integer',
        'exam_included' => 'boolean',
        'exam_taken' => 'boolean',
    ];

    /**
     * Get the tenant that owns the student package.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student for the package.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the package.
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scope a query to only include active packages.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include expired packages.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope a query to only include completed packages.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled packages.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include packages that include exam.
     */
    public function scopeWithExam($query)
    {
        return $query->where('exam_included', true);
    }

    /**
     * Check if package is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if package is expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired' || $this->expiry_date < now();
    }

    /**
     * Check if package is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if package is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if package includes exam.
     */
    public function includesExam(): bool
    {
        return $this->exam_included;
    }

    /**
     * Check if exam has been taken.
     */
    public function hasExamBeenTaken(): bool
    {
        return $this->exam_taken;
    }

    /**
     * Get the remaining theory hours.
     */
    public function getRemainingTheoryHoursAttribute(): int
    {
        return max(0, $this->package->theory_hours - $this->theory_hours_used);
    }

    /**
     * Get the remaining practical hours.
     */
    public function getRemainingPracticalHoursAttribute(): int
    {
        return max(0, $this->package->practical_hours - $this->practical_hours_used);
    }

    /**
     * Get the total remaining hours.
     */
    public function getRemainingHoursAttribute(): int
    {
        return $this->remaining_theory_hours + $this->remaining_practical_hours;
    }

    /**
     * Get the theory hours completion percentage.
     */
    public function getTheoryCompletionPercentageAttribute(): float
    {
        if ($this->package->theory_hours == 0) return 100;
        return ($this->theory_hours_used / $this->package->theory_hours) * 100;
    }

    /**
     * Get the practical hours completion percentage.
     */
    public function getPracticalCompletionPercentageAttribute(): float
    {
        if ($this->package->practical_hours == 0) return 100;
        return ($this->practical_hours_used / $this->package->practical_hours) * 100;
    }

    /**
     * Get the overall completion percentage.
     */
    public function getOverallCompletionPercentageAttribute(): float
    {
        $totalHours = $this->package->total_hours;
        if ($totalHours == 0) return 100;
        
        $usedHours = $this->theory_hours_used + $this->practical_hours_used;
        return ($usedHours / $totalHours) * 100;
    }

    /**
     * Get the formatted price paid.
     */
    public function getFormattedPricePaidAttribute(): string
    {
        return number_format($this->price_paid, 2) . ' MAD';
    }

    /**
     * Get the days until expiry.
     */
    public function getDaysUntilExpiryAttribute(): int
    {
        if ($this->isExpired()) return 0;
        return max(0, now()->diffInDays($this->expiry_date, false));
    }

    /**
     * Get the formatted expiry date.
     */
    public function getFormattedExpiryDateAttribute(): string
    {
        return $this->expiry_date->format('M d, Y');
    }

    /**
     * Get the formatted purchased date.
     */
    public function getFormattedPurchasedDateAttribute(): string
    {
        return $this->purchased_date->format('M d, Y');
    }

    /**
     * Check if package is expiring soon (within 30 days).
     */
    public function isExpiringSoon(): bool
    {
        return $this->isActive() && 
               $this->expiry_date <= now()->addDays(30) && 
               $this->expiry_date >= now();
    }

    /**
     * Check if package has remaining hours.
     */
    public function hasRemainingHours(): bool
    {
        return $this->remaining_hours > 0;
    }

    /**
     * Use theory hours.
     */
    public function useTheoryHours(int $hours): bool
    {
        if ($this->remaining_theory_hours >= $hours) {
            $this->increment('theory_hours_used', $hours);
            return true;
        }
        return false;
    }

    /**
     * Use practical hours.
     */
    public function usePracticalHours(int $hours): bool
    {
        if ($this->remaining_practical_hours >= $hours) {
            $this->increment('practical_hours_used', $hours);
            return true;
        }
        return false;
    }

    /**
     * Mark exam as taken.
     */
    public function markExamAsTaken(): void
    {
        if ($this->includesExam()) {
            $this->update(['exam_taken' => true]);
        }
    }

    /**
     * Mark package as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    /**
     * Mark package as expired.
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }

    /**
     * Cancel package.
     */
    public function cancel(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? ($this->notes . "\nCancelled: " . $reason) : $this->notes,
        ]);
    }

    /**
     * Check if package can be used.
     */
    public function canBeUsed(): bool
    {
        return $this->isActive() && 
               !$this->isExpired() && 
               $this->hasRemainingHours();
    }

    /**
     * Get the package summary.
     */
    public function getSummaryAttribute(): string
    {
        $summary = $this->package->name;
        
        if ($this->package->theory_hours > 0) {
            $summary .= ' - ' . $this->theory_hours_used . '/' . $this->package->theory_hours . 'h theory';
        }
        
        if ($this->package->practical_hours > 0) {
            $summary .= ' - ' . $this->practical_hours_used . '/' . $this->package->practical_hours . 'h practical';
        }
        
        return $summary;
    }
}
