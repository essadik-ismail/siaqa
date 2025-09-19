<?php

namespace App\Http\Requests\TheoryClass;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTheoryClassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('theory_class'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $classId = $this->route('theory_class')->id;

        return [
            'instructor_id' => ['sometimes', 'exists:instructors,id'],
            'class_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('theory_classes', 'class_number')->ignore($classId)
            ],
            'title' => ['sometimes', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'license_category' => ['sometimes', 'in:A,B,C,D,E'],
            'scheduled_at' => ['sometimes', 'date'],
            'completed_at' => ['nullable', 'date', 'after:scheduled_at'],
            'duration_minutes' => ['sometimes', 'integer', 'min:30', 'max:480'],
            'max_students' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'in:scheduled,in_progress,completed,cancelled'],
            'classroom' => ['nullable', 'string', 'max:100'],
            'price_per_student' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'topics_covered' => ['nullable', 'array'],
            'materials' => ['nullable', 'array'],
            'instructor_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'class_number.unique' => 'Class number already exists.',
            'license_category.in' => 'License category must be A, B, C, D, or E.',
            'scheduled_at.date' => 'Scheduled time must be a valid date.',
            'completed_at.after' => 'Completed time must be after scheduled time.',
            'duration_minutes.min' => 'Duration must be at least 30 minutes.',
            'duration_minutes.max' => 'Duration cannot exceed 8 hours.',
            'max_students.min' => 'Maximum students must be at least 1.',
            'max_students.max' => 'Maximum students cannot exceed 100.',
            'status.in' => 'Status must be scheduled, in_progress, completed, or cancelled.',
            'price_per_student.min' => 'Price per student cannot be negative.',
            'price_per_student.max' => 'Price per student cannot exceed 999,999.99.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'instructor_id' => 'instructor',
            'class_number' => 'class number',
            'title' => 'class title',
            'description' => 'description',
            'license_category' => 'license category',
            'scheduled_at' => 'scheduled time',
            'completed_at' => 'completed time',
            'duration_minutes' => 'duration',
            'max_students' => 'maximum students',
            'classroom' => 'classroom',
            'price_per_student' => 'price per student',
            'topics_covered' => 'topics covered',
            'materials' => 'materials',
            'instructor_notes' => 'instructor notes',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $theoryClass = $this->route('theory_class');

            // Validate that instructor is available
            if ($this->has('instructor_id')) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isAvailable()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not available.');
                }
            }

            // Validate that completed classes cannot be modified
            if ($theoryClass->isCompleted() && $this->has('status') && $this->status !== 'completed') {
                $validator->errors()->add('status', 'Completed classes cannot be modified.');
            }

            // Validate that cancelled classes cannot be modified
            if ($theoryClass->isCancelled() && $this->has('status') && $this->status !== 'cancelled') {
                $validator->errors()->add('status', 'Cancelled classes cannot be modified.');
            }

            // Validate that max_students is not less than current_students
            if ($this->has('max_students')) {
                $currentStudents = $theoryClass->current_students;
                if ($this->max_students < $currentStudents) {
                    $validator->errors()->add('max_students', "Maximum students cannot be less than current students ({$currentStudents}).");
                }
            }
        });
    }
}
