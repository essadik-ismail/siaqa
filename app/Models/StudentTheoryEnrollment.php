<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTheoryEnrollment extends Model
{
    use HasFactory;

    protected $table = 'student_theory_enrollments';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'theory_class_id',
        'status',
        'attended',
        'enrolled_at',
        'attended_at',
        'notes',
    ];

    protected $casts = [
        'attended' => 'boolean',
        'enrolled_at' => 'datetime',
        'attended_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns the enrollment.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student for the enrollment.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the theory class for the enrollment.
     */
    public function theoryClass(): BelongsTo
    {
        return $this->belongsTo(TheoryClass::class);
    }

    /**
     * Scope a query to only include enrolled students.
     */
    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    /**
     * Scope a query to only include attended students.
     */
    public function scopeAttended($query)
    {
        return $query->where('status', 'attended');
    }

    /**
     * Scope a query to only include completed enrollments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include dropped enrollments.
     */
    public function scopeDropped($query)
    {
        return $query->where('status', 'dropped');
    }

    /**
     * Scope a query to only include enrollments for a specific student.
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope a query to only include enrollments for a specific class.
     */
    public function scopeForClass($query, $classId)
    {
        return $query->where('theory_class_id', $classId);
    }

    /**
     * Check if enrollment is enrolled.
     */
    public function isEnrolled(): bool
    {
        return $this->status === 'enrolled';
    }

    /**
     * Check if enrollment is attended.
     */
    public function isAttended(): bool
    {
        return $this->status === 'attended' || $this->attended;
    }

    /**
     * Check if enrollment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if enrollment is dropped.
     */
    public function isDropped(): bool
    {
        return $this->status === 'dropped';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'enrolled' => 'Enrolled',
            'attended' => 'Attended',
            'completed' => 'Completed',
            'dropped' => 'Dropped',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'enrolled' => 'blue',
            'attended' => 'green',
            'completed' => 'green',
            'dropped' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the formatted enrolled time.
     */
    public function getFormattedEnrolledAtAttribute(): string
    {
        return $this->enrolled_at ? $this->enrolled_at->format('M d, Y H:i') : '';
    }

    /**
     * Get the formatted attended time.
     */
    public function getFormattedAttendedAtAttribute(): ?string
    {
        return $this->attended_at ? $this->attended_at->format('M d, Y H:i') : null;
    }

    /**
     * Mark as attended.
     */
    public function markAsAttended(): void
    {
        $this->update([
            'status' => 'attended',
            'attended' => true,
            'attended_at' => now(),
        ]);
    }

    /**
     * Mark as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'attended' => true,
        ]);
    }

    /**
     * Mark as dropped.
     */
    public function markAsDropped(string $reason = null): void
    {
        $this->update([
            'status' => 'dropped',
            'notes' => $reason ? ($this->notes . "\nDropped: " . $reason) : $this->notes,
        ]);
    }

    /**
     * Check if enrollment can be marked as attended.
     */
    public function canBeMarkedAsAttended(): bool
    {
        return $this->isEnrolled() && !$this->isAttended();
    }

    /**
     * Check if enrollment can be marked as completed.
     */
    public function canBeMarkedAsCompleted(): bool
    {
        return $this->isAttended() && !$this->isCompleted();
    }

    /**
     * Check if enrollment can be dropped.
     */
    public function canBeDropped(): bool
    {
        return in_array($this->status, ['enrolled', 'attended']);
    }

    /**
     * Get the time until class starts.
     */
    public function getTimeUntilClassAttribute(): ?string
    {
        $class = $this->theoryClass;
        if (!$class || $class->scheduled_at <= now()) {
            return null;
        }

        $diff = now()->diff($class->scheduled_at);
        
        if ($diff->days > 0) {
            return $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' remaining';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' remaining';
        } else {
            return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' remaining';
        }
    }

    /**
     * Get the class title.
     */
    public function getClassTitleAttribute(): string
    {
        return $this->theoryClass ? $this->theoryClass->title : 'Unknown Class';
    }

    /**
     * Get the class scheduled time.
     */
    public function getClassScheduledAtAttribute(): ?string
    {
        return $this->theoryClass ? $this->theoryClass->formatted_scheduled_at : null;
    }

    /**
     * Get the student name.
     */
    public function getStudentNameAttribute(): string
    {
        return $this->student ? $this->student->full_name : 'Unknown Student';
    }
}
