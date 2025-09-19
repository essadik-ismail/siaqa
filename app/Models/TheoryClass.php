<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TheoryClass extends Model
{
    use HasFactory;

    protected $table = 'theory_classes';

    protected $fillable = [
        'tenant_id',
        'instructor_id',
        'class_number',
        'title',
        'description',
        'license_category',
        'scheduled_at',
        'completed_at',
        'duration_minutes',
        'max_students',
        'current_students',
        'status',
        'classroom',
        'price_per_student',
        'topics_covered',
        'materials',
        'instructor_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_minutes' => 'integer',
        'max_students' => 'integer',
        'current_students' => 'integer',
        'price_per_student' => 'decimal:2',
        'topics_covered' => 'array',
        'materials' => 'array',
    ];

    /**
     * Get the tenant that owns the theory class.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }


    /**
     * Get the instructor for the theory class.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the student enrollments for the theory class.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(StudentTheoryEnrollment::class, 'theory_class_id');
    }

    /**
     * Scope a query to only include scheduled classes.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed classes.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled classes.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include classes by license category.
     */
    public function scopeByLicenseCategory($query, $category)
    {
        return $query->where('license_category', $category);
    }

    /**
     * Scope a query to only include classes for a specific instructor.
     */
    public function scopeForInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    /**
     * Scope a query to only include classes for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('scheduled_at', $date);
    }

    /**
     * Scope a query to only include classes for a specific date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('scheduled_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include classes with available spots.
     */
    public function scopeWithAvailableSpots($query)
    {
        return $query->whereRaw('current_students < max_students');
    }

    /**
     * Check if class is scheduled.
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    /**
     * Check if class is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Check if class is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if class is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if class has available spots.
     */
    public function hasAvailableSpots(): bool
    {
        return $this->current_students < $this->max_students;
    }

    /**
     * Get the remaining spots.
     */
    public function getRemainingSpotsAttribute(): int
    {
        return max(0, $this->max_students - $this->current_students);
    }

    /**
     * Get the capacity percentage.
     */
    public function getCapacityPercentageAttribute(): float
    {
        if ($this->max_students == 0) return 0;
        return ($this->current_students / $this->max_students) * 100;
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
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price_per_student, 2) . ' MAD';
    }

    /**
     * Get the license category label.
     */
    public function getLicenseCategoryLabelAttribute(): string
    {
        $categories = [
            'A' => 'Category A (Motorcycles)',
            'B' => 'Category B (Cars)',
            'C' => 'Category C (Trucks)',
            'D' => 'Category D (Buses)',
            'E' => 'Category E (Trailers)',
        ];

        return $categories[$this->license_category] ?? $this->license_category;
    }

    /**
     * Get the formatted scheduled time.
     */
    public function getFormattedScheduledAtAttribute(): string
    {
        return $this->scheduled_at->format('M d, Y H:i');
    }

    /**
     * Get the formatted completed time.
     */
    public function getFormattedCompletedAtAttribute(): ?string
    {
        return $this->completed_at ? $this->completed_at->format('M d, Y H:i') : null;
    }

    /**
     * Get the topics covered as a list.
     */
    public function getTopicsListAttribute(): array
    {
        return $this->topics_covered ?? [];
    }

    /**
     * Get the materials as a list.
     */
    public function getMaterialsListAttribute(): array
    {
        return $this->materials ?? [];
    }

    /**
     * Enroll a student in the class.
     */
    public function enrollStudent(int $studentId): bool
    {
        if (!$this->hasAvailableSpots()) {
            return false;
        }

        // Check if student is already enrolled
        if ($this->enrollments()->where('student_id', $studentId)->exists()) {
            return false;
        }

        $this->enrollments()->create([
            'tenant_id' => $this->tenant_id,
            'student_id' => $studentId,
            'enrolled_at' => now(),
        ]);

        $this->increment('current_students');
        return true;
    }

    /**
     * Unenroll a student from the class.
     */
    public function unenrollStudent(int $studentId): bool
    {
        $enrollment = $this->enrollments()->where('student_id', $studentId)->first();
        
        if (!$enrollment) {
            return false;
        }

        $enrollment->delete();
        $this->decrement('current_students');
        return true;
    }

    /**
     * Mark class as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark class as cancelled.
     */
    public function markAsCancelled(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    /**
     * Check if class is overdue (scheduled time has passed but not completed).
     */
    public function isOverdue(): bool
    {
        return $this->scheduled_at < now() && 
               !in_array($this->status, ['completed', 'cancelled']);
    }

    /**
     * Get the time until class starts.
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
