<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $table = 'exams';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'instructor_id',
        'exam_number',
        'exam_type',
        'license_category',
        'scheduled_at',
        'completed_at',
        'duration_minutes',
        'status',
        'location',
        'exam_fee',
        'score',
        'max_score',
        'exam_results',
        'examiner_notes',
        'feedback',
        'retake_date',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_minutes' => 'integer',
        'exam_fee' => 'decimal:2',
        'score' => 'integer',
        'max_score' => 'integer',
        'exam_results' => 'array',
        'retake_date' => 'date',
    ];

    /**
     * Get the tenant that owns the exam.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student for the exam.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the instructor for the exam.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the payments for the exam.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope a query to only include scheduled exams.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include passed exams.
     */
    public function scopePassed($query)
    {
        return $query->where('status', 'passed');
    }

    /**
     * Scope a query to only include failed exams.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope a query to only include cancelled exams.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include theory exams.
     */
    public function scopeTheory($query)
    {
        return $query->where('exam_type', 'theory');
    }

    /**
     * Scope a query to only include practical exams.
     */
    public function scopePractical($query)
    {
        return $query->where('exam_type', 'practical');
    }

    /**
     * Scope a query to only include simulation exams.
     */
    public function scopeSimulation($query)
    {
        return $query->where('exam_type', 'simulation');
    }

    /**
     * Scope a query to only include exams for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('scheduled_at', $date);
    }

    /**
     * Scope a query to only include exams for a specific date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('scheduled_at', [$startDate, $endDate]);
    }

    /**
     * Check if exam is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if exam is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if exam is passed.
     */
    public function isPassed(): bool
    {
        return $this->status === 'passed';
    }

    /**
     * Check if exam is failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if exam is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if exam is a no-show.
     */
    public function isNoShow(): bool
    {
        return $this->status === 'no_show';
    }

    /**
     * Check if exam is theory.
     */
    public function isTheory(): bool
    {
        return $this->exam_type === 'theory';
    }

    /**
     * Check if exam is practical.
     */
    public function isPractical(): bool
    {
        return $this->exam_type === 'practical';
    }

    /**
     * Check if exam is simulation.
     */
    public function isSimulation(): bool
    {
        return $this->exam_type === 'simulation';
    }

    /**
     * Get the score percentage.
     */
    public function getScorePercentageAttribute(): float
    {
        if ($this->max_score == 0) return 0;
        return ($this->score / $this->max_score) * 100;
    }

    /**
     * Get the formatted score.
     */
    public function getFormattedScoreAttribute(): string
    {
        if ($this->score === null) return 'Not scored';
        return $this->score . '/' . $this->max_score . ' (' . round($this->score_percentage, 1) . '%)';
    }

    /**
     * Get the duration in hours.
     */
    public function getDurationHoursAttribute(): float
    {
        return $this->duration_minutes / 60;
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        
        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }
        
        return "{$minutes}m";
    }

    /**
     * Mark exam as passed.
     */
    public function markAsPassed(int $score, array $results = null): void
    {
        $this->update([
            'status' => 'passed',
            'score' => $score,
            'exam_results' => $results,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark exam as failed.
     */
    public function markAsFailed(int $score, array $results = null): void
    {
        $this->update([
            'status' => 'failed',
            'score' => $score,
            'exam_results' => $results,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark exam as cancelled.
     */
    public function markAsCancelled(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Mark exam as no-show.
     */
    public function markAsNoShow(): void
    {
        $this->update([
            'status' => 'no_show',
        ]);
    }

    /**
     * Check if exam is overdue (scheduled time has passed but not completed).
     */
    public function isOverdue(): bool
    {
        return $this->scheduled_at < now() && 
               !in_array($this->status, ['passed', 'failed', 'cancelled', 'no_show']);
    }

    /**
     * Check if exam can be retaken.
     */
    public function canBeRetaken(): bool
    {
        return $this->isFailed() && $this->retake_date && $this->retake_date >= now()->toDateString();
    }

    /**
     * Get the time until exam starts.
     */
    public function getTimeUntilStartAttribute(): ?string
    {
        if ($this->scheduled_at <= now()) {
            return null;
        }

        $diff = now()->diff($this->scheduled_at);
        
        if ($diff->days > 0) {
            return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' remaining';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' remaining';
        } else {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' remaining';
        }
    }
}
