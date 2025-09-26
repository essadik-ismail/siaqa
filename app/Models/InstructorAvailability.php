<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstructorAvailability extends Model
{
    use HasFactory;

    protected $table = 'instructor_availability';

    protected $fillable = [
        'tenant_id',
        'instructor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_available',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_available' => 'boolean',
    ];

    /**
     * Get the tenant that owns the availability record.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the instructor for the availability record.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Scope a query to only include available slots.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include slots for a specific day.
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Scope a query to only include slots for a specific instructor.
     */
    public function scopeForInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    /**
     * Check if slot is available.
     */
    public function isAvailable(): bool
    {
        return $this->is_available;
    }

    /**
     * Get the day of week label.
     */
    public function getDayLabelAttribute(): string
    {
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        return $days[$this->day_of_week] ?? $this->day_of_week;
    }

    /**
     * Get the formatted time range.
     */
    public function getFormattedTimeRangeAttribute(): string
    {
        $startTime = $this->start_time ? $this->start_time->format('H:i') : '';
        $endTime = $this->end_time ? $this->end_time->format('H:i') : '';
        return $startTime . ' - ' . $endTime;
    }

    /**
     * Get the duration in minutes.
     */
    public function getDurationMinutesAttribute(): int
    {
        return $this->start_time->diffInMinutes($this->end_time);
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
     * Check if time slot overlaps with another time slot.
     */
    public function overlapsWith(InstructorAvailability $other): bool
    {
        if ($this->day_of_week !== $other->day_of_week) {
            return false;
        }

        return $this->start_time < $other->end_time && $this->end_time > $other->start_time;
    }

    /**
     * Check if time slot contains a specific time.
     */
    public function containsTime(string $time): bool
    {
        $timeObj = \Carbon\Carbon::createFromFormat('H:i', $time);
        return $timeObj >= $this->start_time && $timeObj <= $this->end_time;
    }

    /**
     * Get available time slots within this range.
     */
    public function getAvailableTimeSlots(int $slotDuration = 60): array
    {
        $slots = [];
        $current = $this->start_time->copy();
        
        while ($current->addMinutes($slotDuration) <= $this->end_time) {
            $slots[] = [
                'start' => $current->copy()->subMinutes($slotDuration)->format('H:i'),
                'end' => $current->format('H:i'),
            ];
        }
        
        return $slots;
    }

    /**
     * Toggle availability.
     */
    public function toggleAvailability(): void
    {
        $this->update(['is_available' => !$this->is_available]);
    }

    /**
     * Mark as available.
     */
    public function markAsAvailable(): void
    {
        $this->update(['is_available' => true]);
    }

    /**
     * Mark as unavailable.
     */
    public function markAsUnavailable(): void
    {
        $this->update(['is_available' => false]);
    }
}
