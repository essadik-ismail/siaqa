<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $table = 'lessons';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'instructor_id',
        'vehicle_id',
        'lesson_number',
        'lesson_type',
        'title',
        'description',
        'scheduled_at',
        'completed_at',
        'duration_minutes',
        'status',
        'location',
        'price',
        'skills_covered',
        'student_rating',
        'instructor_notes',
        'student_feedback',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_minutes' => 'integer',
        'price' => 'decimal:2',
        'student_rating' => 'integer',
        'skills_covered' => 'array',
    ];

    /**
     * Get the tenant that owns the lesson.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student for the lesson.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the instructor for the lesson.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the vehicle for the lesson.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'vehicle_id');
    }

    /**
     * Get the payments for the lesson.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the student progress records for the lesson.
     */
    public function studentProgress(): HasMany
    {
        return $this->hasMany(StudentProgress::class);
    }

    /**
     * Get the vehicle assignments for the lesson.
     */
    public function vehicleAssignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    /**
     * Scope a query to only include scheduled lessons.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed lessons.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled lessons.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include practical lessons.
     */
    public function scopePractical($query)
    {
        return $query->where('lesson_type', 'practical');
    }

    /**
     * Scope a query to only include theory lessons.
     */
    public function scopeTheory($query)
    {
        return $query->where('lesson_type', 'theory');
    }

    /**
     * Scope a query to only include simulation lessons.
     */
    public function scopeSimulation($query)
    {
        return $query->where('lesson_type', 'simulation');
    }

    /**
     * Scope a query to only include lessons for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('scheduled_at', $date);
    }

    /**
     * Scope a query to only include lessons for a specific date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('scheduled_at', [$startDate, $endDate]);
    }

    /**
     * Check if lesson is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if lesson is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if lesson is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if lesson is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if lesson is a no-show.
     */
    public function isNoShow(): bool
    {
        return $this->status === 'no_show';
    }

    /**
     * Check if lesson is practical.
     */
    public function isPractical(): bool
    {
        return $this->lesson_type === 'practical';
    }

    /**
     * Check if lesson is theory.
     */
    public function isTheory(): bool
    {
        return $this->lesson_type === 'theory';
    }

    /**
     * Check if lesson is simulation.
     */
    public function isSimulation(): bool
    {
        return $this->lesson_type === 'simulation';
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
     * Mark lesson as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark lesson as cancelled.
     */
    public function markAsCancelled(string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'cancellation_reason' => $reason,
        ]);
    }

    /**
     * Mark lesson as no-show.
     */
    public function markAsNoShow(): void
    {
        $this->update([
            'status' => 'no_show',
        ]);
    }

    /**
     * Check if lesson is overdue (scheduled time has passed but not completed).
     */
    public function isOverdue(): bool
    {
        return $this->scheduled_at < now() && 
               !in_array($this->status, ['completed', 'cancelled', 'no_show']);
    }

    /**
     * Get the time until lesson starts.
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
