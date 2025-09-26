<?php

namespace App\Http\Requests\InstructorAvailability;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorAvailabilityRequest extends FormRequest
{
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'instructor_id' => ['sometimes', 'exists:instructors,id'],
            'day_of_week' => ['sometimes', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['sometimes', 'date_format:H:i'],
            'end_time' => ['sometimes', 'date_format:H:i', 'after:start_time'],
            'is_available' => ['boolean'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'day_of_week.in' => 'Day of week must be monday, tuesday, wednesday, thursday, friday, saturday, or sunday.',
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.date_format' => 'End time must be in HH:MM format.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'instructor_id' => 'instructor',
            'day_of_week' => 'day of week',
            'start_time' => 'start time',
            'end_time' => 'end time',
            'is_available' => 'is available',
            'notes' => 'notes',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $availability = $this->route('instructor_availability');

            // Validate that instructor is active
            if ($this->has('instructor_id')) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Get current values or use provided values
            $instructorId = $this->has('instructor_id') ? $this->instructor_id : $availability->instructor_id;
            $dayOfWeek = $this->has('day_of_week') ? $this->day_of_week : $availability->day_of_week;
            $startTime = $this->has('start_time') ? $this->start_time : $availability->start_time;
            $endTime = $this->has('end_time') ? $this->end_time : $availability->end_time;

            // Validate that there's no overlapping availability for the same instructor and day
            if ($instructorId && $dayOfWeek && $startTime && $endTime) {
                $overlapping = \App\Models\InstructorAvailability::where('instructor_id', $instructorId)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('is_available', true)
                    ->where('id', '!=', $availability->id)
                    ->where(function ($query) use ($startTime, $endTime) {
                        $query->whereBetween('start_time', [$startTime, $endTime])
                            ->orWhereBetween('end_time', [$startTime, $endTime])
                            ->orWhere(function ($q) use ($startTime, $endTime) {
                                $q->where('start_time', '<=', $startTime)
                                  ->where('end_time', '>=', $endTime);
                            });
                    })
                    ->exists();

                if ($overlapping) {
                    $validator->errors()->add('start_time', 'There is already an availability slot that overlaps with this time range.');
                }
            }
        });
    }
}
