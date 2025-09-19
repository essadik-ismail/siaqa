<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instructor extends Model
{
    use HasFactory;

    protected $table = 'instructors';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'employee_number',
        'license_number',
        'license_expiry',
        'license_categories',
        'years_experience',
        'hourly_rate',
        'max_students',
        'current_students',
        'status',
        'availability_schedule',
        'specializations',
        'notes',
        'is_available',
    ];

    protected $casts = [
        'license_expiry' => 'date',
        'years_experience' => 'integer',
        'hourly_rate' => 'decimal:2',
        'max_students' => 'integer',
        'current_students' => 'integer',
        'is_available' => 'boolean',
        'availability_schedule' => 'array',
        'specializations' => 'array',
    ];

    /**
     * Get the tenant that owns the instructor.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the user account for the instructor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lessons for the instructor.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /**
     * Get the exams for the instructor.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the student progress records for the instructor.
     */
    public function studentProgress(): HasMany
    {
        return $this->hasMany(StudentProgress::class);
    }

    /**
     * Get the availability records for the instructor.
     */
    public function availability(): HasMany
    {
        return $this->hasMany(InstructorAvailability::class);
    }

    /**
     * Get the vehicle assignments for the instructor.
     */
    public function vehicleAssignments(): HasMany
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    /**
     * Get the theory classes for the instructor.
     */
    public function theoryClasses(): HasMany
    {
        return $this->hasMany(TheoryClass::class);
    }

    /**
     * Get the notifications for the instructor.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Scope a query to only include active instructors.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include available instructors.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)
                    ->where('status', 'active');
    }

    /**
     * Check if instructor is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if instructor is available.
     */
    public function isAvailable(): bool
    {
        return $this->is_available && $this->isActive();
    }

    /**
     * Check if instructor is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if instructor has capacity for more students.
     */
    public function hasCapacity(): bool
    {
        return $this->current_students < $this->max_students;
    }

    /**
     * Get the remaining student capacity.
     */
    public function getRemainingCapacityAttribute(): int
    {
        return max(0, $this->max_students - $this->current_students);
    }

    /**
     * Check if instructor's license is expired.
     */
    public function isLicenseExpired(): bool
    {
        return $this->license_expiry < now();
    }

    /**
     * Check if instructor's license expires soon (within 30 days).
     */
    public function isLicenseExpiringSoon(): bool
    {
        return $this->license_expiry < now()->addDays(30);
    }

    /**
     * Get the full name of the instructor.
     */
    public function getFullNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Unknown';
    }

    /**
     * Get the email of the instructor.
     */
    public function getEmailAttribute(): string
    {
        return $this->user ? $this->user->email : '';
    }

    /**
     * Get the phone of the instructor.
     */
    public function getPhoneAttribute(): string
    {
        return $this->user ? $this->user->phone : '';
    }

    /**
     * Update student count.
     */
    public function updateStudentCount(): void
    {
        $this->current_students = $this->lessons()
            ->whereHas('student', function ($query) {
                $query->where('status', 'active');
            })
            ->distinct('student_id')
            ->count();
        
        $this->save();
    }
}
