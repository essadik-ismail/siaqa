<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytics extends Model
{
    use HasFactory;

    protected $table = 'analytics';

    protected $fillable = [
        'tenant_id',
        'date',
        'metric_name',
        'metric_value',
        'dimensions',
    ];

    protected $casts = [
        'date' => 'date',
        'metric_value' => 'decimal:2',
        'dimensions' => 'array',
    ];

    /**
     * Get the tenant that owns the analytics record.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope a query to only include metrics by name.
     */
    public function scopeByMetric($query, $metricName)
    {
        return $query->where('metric_name', $metricName);
    }

    /**
     * Scope a query to only include metrics for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope a query to only include metrics for a specific date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to only include metrics for a specific month.
     */
    public function scopeForMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    /**
     * Scope a query to only include metrics for a specific year.
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }

    /**
     * Scope a query to only include metrics with specific dimensions.
     */
    public function scopeWithDimensions($query, array $dimensions)
    {
        foreach ($dimensions as $key => $value) {
            $query->whereJsonContains("dimensions->{$key}", $value);
        }
        return $query;
    }

    /**
     * Get the metric name label.
     */
    public function getMetricNameLabelAttribute(): string
    {
        $metrics = [
            'revenue' => 'Revenue',
            'student_count' => 'Student Count',
            'lesson_count' => 'Lesson Count',
            'exam_count' => 'Exam Count',
            'instructor_count' => 'Instructor Count',
            'vehicle_count' => 'Vehicle Count',
            'completion_rate' => 'Completion Rate',
            'pass_rate' => 'Pass Rate',
            'average_rating' => 'Average Rating',
            'active_students' => 'Active Students',
            'new_students' => 'New Students',
            'graduated_students' => 'Graduated Students',
            'cancelled_lessons' => 'Cancelled Lessons',
            'no_show_lessons' => 'No-Show Lessons',
            'overdue_payments' => 'Overdue Payments',
            'vehicle_utilization' => 'Vehicle Utilization',
            'instructor_utilization' => 'Instructor Utilization',
        ];

        return $metrics[$this->metric_name] ?? $this->metric_name;
    }

    /**
     * Get the formatted metric value.
     */
    public function getFormattedMetricValueAttribute(): string
    {
        if (in_array($this->metric_name, ['revenue', 'total_paid', 'total_due', 'price', 'exam_fee', 'hourly_rate'])) {
            return number_format($this->metric_value, 2) . ' MAD';
        }
        
        if (in_array($this->metric_name, ['completion_rate', 'pass_rate', 'average_rating', 'utilization_rate'])) {
            return number_format($this->metric_value, 1) . '%';
        }
        
        return number_format($this->metric_value);
    }

    /**
     * Get the dimensions as array.
     */
    public function getDimensionsArrayAttribute(): array
    {
        return $this->dimensions ?? [];
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M d, Y');
    }

    /**
     * Get the metric value as integer.
     */
    public function getMetricValueIntAttribute(): int
    {
        return (int) $this->metric_value;
    }

    /**
     * Get the metric value as float.
     */
    public function getMetricValueFloatAttribute(): float
    {
        return (float) $this->metric_value;
    }

    /**
     * Check if metric is a percentage.
     */
    public function isPercentage(): bool
    {
        return in_array($this->metric_name, [
            'completion_rate', 'pass_rate', 'average_rating', 
            'utilization_rate', 'success_rate'
        ]);
    }

    /**
     * Check if metric is a currency value.
     */
    public function isCurrency(): bool
    {
        return in_array($this->metric_name, [
            'revenue', 'total_paid', 'total_due', 'price', 
            'exam_fee', 'hourly_rate', 'amount'
        ]);
    }

    /**
     * Check if metric is a count.
     */
    public function isCount(): bool
    {
        return in_array($this->metric_name, [
            'student_count', 'lesson_count', 'exam_count', 
            'instructor_count', 'vehicle_count', 'active_students',
            'new_students', 'graduated_students', 'cancelled_lessons',
            'no_show_lessons', 'overdue_payments'
        ]);
    }

    /**
     * Get the metric category.
     */
    public function getMetricCategoryAttribute(): string
    {
        if ($this->isCurrency()) return 'financial';
        if ($this->isPercentage()) return 'performance';
        if ($this->isCount()) return 'count';
        return 'other';
    }

    /**
     * Get the metric trend (compared to previous period).
     */
    public function getTrendAttribute(): ?string
    {
        // This would need to be calculated by comparing with previous period
        // For now, return null
        return null;
    }

    /**
     * Get the metric color for display.
     */
    public function getMetricColorAttribute(): string
    {
        if ($this->isPercentage()) {
            if ($this->metric_value >= 80) return 'green';
            if ($this->metric_value >= 60) return 'yellow';
            return 'red';
        }
        
        if ($this->isCurrency()) {
            return 'blue';
        }
        
        return 'gray';
    }

    /**
     * Get the metric icon.
     */
    public function getMetricIconAttribute(): string
    {
        return match($this->metric_name) {
            'revenue' => '💰',
            'student_count' => '👥',
            'lesson_count' => '📚',
            'exam_count' => '📝',
            'instructor_count' => '👨‍🏫',
            'vehicle_count' => '🚗',
            'completion_rate' => '✅',
            'pass_rate' => '🎯',
            'average_rating' => '⭐',
            default => '📊'
        };
    }
}
