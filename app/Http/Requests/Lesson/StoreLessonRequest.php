<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Lesson::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'vehicle_id' => ['nullable', 'exists:vehicules,id'],
            'lesson_number' => ['required', 'string', 'max:50', 'unique:lessons,lesson_number'],
            'lesson_type' => ['required', 'in:theory,practical,simulation'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['required', 'integer', 'min:15', 'max:480'],
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled,no_show'],
            'location' => ['nullable', 'string', 'max:200'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'skills_covered' => ['nullable', 'array'],
            'student_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'instructor_notes' => ['nullable', 'string', 'max:1000'],
            'student_feedback' => ['nullable', 'string', 'max:1000'],
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Student is required.',
            'student_id.exists' => 'Selected student does not exist.',
            'instructor_id.required' => 'Instructor is required.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'lesson_number.required' => 'Lesson number is required.',
            'lesson_number.unique' => 'Lesson number already exists.',
            'lesson_type.required' => 'Lesson type is required.',
            'lesson_type.in' => 'Lesson type must be theory, practical, or simulation.',
            'title.required' => 'Lesson title is required.',
            'scheduled_at.required' => 'Scheduled time is required.',
            'scheduled_at.after' => 'Scheduled time must be in the future.',
            'duration_minutes.required' => 'Duration is required.',
            'duration_minutes.min' => 'Duration must be at least 15 minutes.',
            'duration_minutes.max' => 'Duration cannot exceed 8 hours.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be scheduled, in_progress, completed, cancelled, or no_show.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price cannot be negative.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'student_rating.min' => 'Student rating must be at least 1.',
            'student_rating.max' => 'Student rating cannot exceed 5.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'instructor_id' => 'instructor',
            'vehicle_id' => 'vehicle',
            'lesson_number' => 'lesson number',
            'lesson_type' => 'lesson type',
            'title' => 'lesson title',
            'description' => 'description',
            'scheduled_at' => 'scheduled time',
            'duration_minutes' => 'duration',
            'location' => 'location',
            'price' => 'price',
            'skills_covered' => 'skills covered',
            'student_rating' => 'student rating',
            'instructor_notes' => 'instructor notes',
            'student_feedback' => 'student feedback',
            'cancellation_reason' => 'cancellation reason',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'status' => $this->status ?? 'scheduled',
            'duration_minutes' => $this->duration_minutes ?? 60,
            'price' => $this->price ?? 0,
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that vehicle is required for practical lessons
            if ($this->lesson_type === 'practical' && !$this->vehicle_id) {
                $validator->errors()->add('vehicle_id', 'Vehicle is required for practical lessons.');
            }

            // Validate that instructor is available at the scheduled time
            if ($this->instructor_id && $this->scheduled_at) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isAvailable()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not available.');
                }
            }

            // Validate that student is active
            if ($this->student_id) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }
        });
    }
}
