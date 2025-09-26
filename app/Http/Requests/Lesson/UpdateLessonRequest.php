<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLessonRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $lessonId = $this->route('lesson')->id;

        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'instructor_id' => ['sometimes', 'exists:instructors,id'],
            'vehicle_id' => ['nullable', 'exists:vehicules,id'],
            'lesson_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('lessons', 'lesson_number')->ignore($lessonId)
            ],
            'lesson_type' => ['sometimes', 'in:theory,practical,simulation'],
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'scheduled_at' => ['sometimes', 'date'],
            'completed_at' => ['nullable', 'date', 'after:scheduled_at'],
            'duration_minutes' => ['sometimes', 'integer', 'min:15', 'max:480'],
            'status' => ['sometimes', 'in:scheduled,in_progress,completed,cancelled,no_show'],
            'location' => ['nullable', 'string', 'max:200'],
            'price' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
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
            'student_id.exists' => 'Selected student does not exist.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'lesson_number.unique' => 'Lesson number already exists.',
            'lesson_type.in' => 'Lesson type must be theory, practical, or simulation.',
            'scheduled_at.date' => 'Scheduled time must be a valid date.',
            'completed_at.after' => 'Completed time must be after scheduled time.',
            'duration_minutes.min' => 'Duration must be at least 15 minutes.',
            'duration_minutes.max' => 'Duration cannot exceed 8 hours.',
            'status.in' => 'Status must be scheduled, in_progress, completed, cancelled, or no_show.',
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
            'completed_at' => 'completed time',
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
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $lesson = $this->route('lesson');

            // Validate that vehicle is required for practical lessons
            if ($this->has('lesson_type') && $this->lesson_type === 'practical' && !$this->vehicle_id) {
                $validator->errors()->add('vehicle_id', 'Vehicle is required for practical lessons.');
            }

            // Validate that instructor is available at the scheduled time
            if ($this->has('instructor_id') && $this->has('scheduled_at')) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isAvailable()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not available.');
                }
            }

            // Validate that student is active
            if ($this->has('student_id')) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that completed lessons cannot be modified
            if ($lesson->isCompleted() && $this->has('status') && $this->status !== 'completed') {
                $validator->errors()->add('status', 'Completed lessons cannot be modified.');
            }

            // Validate that cancelled lessons cannot be modified
            if ($lesson->isCancelled() && $this->has('status') && $this->status !== 'cancelled') {
                $validator->errors()->add('status', 'Cancelled lessons cannot be modified.');
            }
        });
    }
}
