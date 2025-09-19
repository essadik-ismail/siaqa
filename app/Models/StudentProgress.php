<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProgress extends Model
{
    use HasFactory;

    protected $table = 'student_progress';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'lesson_id',
        'instructor_id',
        'skill_category',
        'skill_name',
        'skill_level',
        'hours_practiced',
        'attempts',
        'success_rate',
        'instructor_notes',
        'assessment_criteria',
        'is_required',
        'is_completed',
        'last_practiced',
    ];

    protected $casts = [
        'hours_practiced' => 'integer',
        'attempts' => 'integer',
        'success_rate' => 'integer',
        'is_required' => 'boolean',
        'is_completed' => 'boolean',
        'last_practiced' => 'date',
        'assessment_criteria' => 'array',
    ];

    /**
     * Get the tenant that owns the progress record.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the student for the progress record.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the lesson for the progress record.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the instructor for the progress record.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Scope a query to only include completed skills.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to only include required skills.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope a query to only include skills by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('skill_category', $category);
    }

    /**
     * Scope a query to only include skills by level.
     */
    public function scopeByLevel($query, $level)
    {
        return $query->where('skill_level', $level);
    }

    /**
     * Scope a query to only include beginner skills.
     */
    public function scopeBeginner($query)
    {
        return $query->where('skill_level', 'beginner');
    }

    /**
     * Scope a query to only include intermediate skills.
     */
    public function scopeIntermediate($query)
    {
        return $query->where('skill_level', 'intermediate');
    }

    /**
     * Scope a query to only include advanced skills.
     */
    public function scopeAdvanced($query)
    {
        return $query->where('skill_level', 'advanced');
    }

    /**
     * Scope a query to only include mastered skills.
     */
    public function scopeMastered($query)
    {
        return $query->where('skill_level', 'mastered');
    }

    /**
     * Check if skill is completed.
     */
    public function isCompleted(): bool
    {
        return $this->is_completed;
    }

    /**
     * Check if skill is required.
     */
    public function isRequired(): bool
    {
        return $this->is_required;
    }

    /**
     * Check if skill is at beginner level.
     */
    public function isBeginner(): bool
    {
        return $this->skill_level === 'beginner';
    }

    /**
     * Check if skill is at intermediate level.
     */
    public function isIntermediate(): bool
    {
        return $this->skill_level === 'intermediate';
    }

    /**
     * Check if skill is at advanced level.
     */
    public function isAdvanced(): bool
    {
        return $this->skill_level === 'advanced';
    }

    /**
     * Check if skill is mastered.
     */
    public function isMastered(): bool
    {
        return $this->skill_level === 'mastered';
    }

    /**
     * Get the skill level label.
     */
    public function getSkillLevelLabelAttribute(): string
    {
        $levels = [
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'mastered' => 'Mastered',
        ];

        return $levels[$this->skill_level] ?? $this->skill_level;
    }

    /**
     * Get the skill level color for display.
     */
    public function getSkillLevelColorAttribute(): string
    {
        return match($this->skill_level) {
            'beginner' => 'red',
            'intermediate' => 'yellow',
            'advanced' => 'blue',
            'mastered' => 'green',
            default => 'gray'
        };
    }

    /**
     * Get the success rate percentage.
     */
    public function getSuccessRatePercentageAttribute(): float
    {
        return $this->success_rate;
    }

    /**
     * Get the formatted success rate.
     */
    public function getFormattedSuccessRateAttribute(): string
    {
        return $this->success_rate . '%';
    }

    /**
     * Get the hours practiced in decimal format.
     */
    public function getHoursPracticedDecimalAttribute(): float
    {
        return $this->hours_practiced / 60;
    }

    /**
     * Get the formatted hours practiced.
     */
    public function getFormattedHoursPracticedAttribute(): string
    {
        $hours = floor($this->hours_practiced / 60);
        $minutes = $this->hours_practiced % 60;
        
        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m" : "{$hours}h";
        }
        
        return "{$minutes}m";
    }

    /**
     * Mark skill as completed.
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'skill_level' => 'mastered',
        ]);
    }

    /**
     * Update skill level.
     */
    public function updateSkillLevel(string $level): void
    {
        $this->update(['skill_level' => $level]);
    }

    /**
     * Add practice time.
     */
    public function addPracticeTime(int $minutes): void
    {
        $this->increment('hours_practiced', $minutes);
        $this->update(['last_practiced' => now()]);
    }

    /**
     * Record an attempt.
     */
    public function recordAttempt(bool $successful = false): void
    {
        $this->increment('attempts');
        
        if ($successful) {
            $this->increment('success_rate');
        }
        
        $this->update(['last_practiced' => now()]);
    }

    /**
     * Calculate success rate.
     */
    public function calculateSuccessRate(): void
    {
        if ($this->attempts > 0) {
            $successfulAttempts = $this->success_rate;
            $this->update(['success_rate' => round(($successfulAttempts / $this->attempts) * 100)]);
        }
    }

    /**
     * Check if skill needs more practice.
     */
    public function needsMorePractice(): bool
    {
        return !$this->is_completed && 
               ($this->success_rate < 70 || $this->attempts < 3);
    }

    /**
     * Get the progress percentage.
     */
    public function getProgressPercentageAttribute(): float
    {
        return match($this->skill_level) {
            'beginner' => 25,
            'intermediate' => 50,
            'advanced' => 75,
            'mastered' => 100,
            default => 0
        };
    }
}
