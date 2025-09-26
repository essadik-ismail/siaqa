<?php

namespace App\Http\Requests\InstructorAvailability;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstructorAvailabilityRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'instructor_id' => ['required', 'exists:instructors,id'],
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
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
            'instructor_id.required' => 'Instructor is required.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'day_of_week.required' => 'Day of week is required.',
            'day_of_week.in' => 'Day of week must be monday, tuesday, wednesday, thursday, friday, saturday, or sunday.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time must be in HH:MM format.',
            'end_time.required' => 'End time is required.',
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'is_available' => $this->boolean('is_available', true),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that instructor is active
            if ($this->instructor_id) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Validate that there's no overlapping availability for the same instructor and day
            if ($this->instructor_id && $this->day_of_week && $this->start_time && $this->end_time) {
                $overlapping = \App\Models\InstructorAvailability::where('instructor_id', $this->instructor_id)
                    ->where('day_of_week', $this->day_of_week)
                    ->where('is_available', true)
                    ->where(function ($query) {
                        $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                            ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                            ->orWhere(function ($q) {
                                $q->where('start_time', '<=', $this->start_time)
                                  ->where('end_time', '>=', $this->end_time);
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
