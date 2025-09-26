<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleAssignment extends Model
{
    use HasFactory;

    protected $table = 'vehicle_assignments';

    protected $fillable = [
        'tenant_id',
        'vehicle_id',
        'instructor_id',
        'lesson_id',
        'assigned_at',
        'returned_at',
        'status',
        'odometer_start',
        'odometer_end',
        'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'returned_at' => 'datetime',
        'odometer_start' => 'integer',
        'odometer_end' => 'integer',
    ];

    /**
     * Get the tenant that owns the assignment.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the vehicle for the assignment.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicule::class, 'vehicle_id');
    }

    /**
     * Get the instructor for the assignment.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Get the lesson for the assignment.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Scope a query to only include assigned vehicles.
     */
    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    /**
     * Scope a query to only include vehicles in use.
     */
    public function scopeInUse($query)
    {
        return $query->where('status', 'in_use');
    }

    /**
     * Scope a query to only include returned vehicles.
     */
    public function scopeReturned($query)
    {
        return $query->where('status', 'returned');
    }

    /**
     * Scope a query to only include vehicles in maintenance.
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope a query to only include assignments for a specific vehicle.
     */
    public function scopeForVehicle($query, $vehicleId)
    {
        return $query->where('vehicle_id', $vehicleId);
    }

    /**
     * Scope a query to only include assignments for a specific instructor.
     */
    public function scopeForInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    /**
     * Scope a query to only include assignments for a specific lesson.
     */
    public function scopeForLesson($query, $lessonId)
    {
        return $query->where('lesson_id', $lessonId);
    }

    /**
     * Scope a query to only include assignments for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('assigned_at', $date);
    }

    /**
     * Scope a query to only include assignments for a specific date range.
     */
    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('assigned_at', [$startDate, $endDate]);
    }

    /**
     * Check if assignment is assigned.
     */
    public function isAssigned(): bool
    {
        return $this->status === 'assigned';
    }

    /**
     * Check if assignment is in use.
     */
    public function isInUse(): bool
    {
        return $this->status === 'in_use';
    }

    /**
     * Check if assignment is returned.
     */
    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    /**
     * Check if assignment is in maintenance.
     */
    public function isInMaintenance(): bool
    {
        return $this->status === 'maintenance';
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'assigned' => 'Assigned',
            'in_use' => 'In Use',
            'returned' => 'Returned',
            'maintenance' => 'Maintenance',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'assigned' => 'blue',
            'in_use' => 'green',
            'returned' => 'gray',
            'maintenance' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the duration in minutes.
     */
    public function getDurationMinutesAttribute(): int
    {
        if (!$this->returned_at) {
            return now()->diffInMinutes($this->assigned_at);
        }
        
        return $this->assigned_at->diffInMinutes($this->returned_at);
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
     * Get the distance traveled.
     */
    public function getDistanceTraveledAttribute(): int
    {
        if (!$this->odometer_start || !$this->odometer_end) {
            return 0;
        }
        
        return max(0, $this->odometer_end - $this->odometer_start);
    }

    /**
     * Get the formatted distance traveled.
     */
    public function getFormattedDistanceAttribute(): string
    {
        return number_format($this->distance_traveled) . ' km';
    }

    /**
     * Get the formatted assigned time.
     */
    public function getFormattedAssignedAtAttribute(): string
    {
        return $this->assigned_at ? $this->assigned_at->format('M d, Y H:i') : '';
    }

    /**
     * Get the formatted returned time.
     */
    public function getFormattedReturnedAtAttribute(): ?string
    {
        return $this->returned_at ? $this->returned_at->format('M d, Y H:i') : null;
    }

    /**
     * Mark as in use.
     */
    public function markAsInUse(): void
    {
        $this->update(['status' => 'in_use']);
    }

    /**
     * Mark as returned.
     */
    public function markAsReturned(int $odometerEnd = null): void
    {
        $this->update([
            'status' => 'returned',
            'returned_at' => now(),
            'odometer_end' => $odometerEnd,
        ]);
    }

    /**
     * Mark as in maintenance.
     */
    public function markAsInMaintenance(): void
    {
        $this->update(['status' => 'maintenance']);
    }

    /**
     * Check if assignment is overdue (assigned for too long).
     */
    public function isOverdue(int $maxHours = 8): bool
    {
        return $this->isInUse() && $this->duration_hours > $maxHours;
    }

    /**
     * Check if assignment is active (assigned or in use).
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['assigned', 'in_use']);
    }

    /**
     * Get the time until assignment expires.
     */
    public function getTimeUntilExpiryAttribute(): ?string
    {
        if (!$this->isActive()) {
            return null;
        }

        $maxDuration = 8; // 8 hours
        $remainingMinutes = ($maxDuration * 60) - $this->duration_minutes;
        
        if ($remainingMinutes <= 0) {
            return 'Expired';
        }

        $hours = floor($remainingMinutes / 60);
        $minutes = $remainingMinutes % 60;
        
        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}m remaining" : "{$hours}h remaining";
        }
        
        return "{$minutes}m remaining";
    }
}
