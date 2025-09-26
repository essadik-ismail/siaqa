<?php

namespace App\Http\Requests\TheoryClass;

use Illuminate\Foundation\Http\FormRequest;

class StoreTheoryClassRequest extends FormRequest
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
            'class_number' => ['required', 'string', 'max:50', 'unique:theory_classes,class_number'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'license_category' => ['required', 'in:A,B,C,D,E'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'duration_minutes' => ['required', 'integer', 'min:30', 'max:480'],
            'max_students' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:scheduled,in_progress,completed,cancelled'],
            'classroom' => ['nullable', 'string', 'max:100'],
            'price_per_student' => ['required', 'numeric', 'min:0', 'max:999999.99'],
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
            'instructor_id.required' => 'Instructor is required.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'class_number.required' => 'Class number is required.',
            'class_number.unique' => 'Class number already exists.',
            'title.required' => 'Class title is required.',
            'license_category.required' => 'License category is required.',
            'license_category.in' => 'License category must be A, B, C, D, or E.',
            'scheduled_at.required' => 'Scheduled time is required.',
            'scheduled_at.after' => 'Scheduled time must be in the future.',
            'duration_minutes.required' => 'Duration is required.',
            'duration_minutes.min' => 'Duration must be at least 30 minutes.',
            'duration_minutes.max' => 'Duration cannot exceed 8 hours.',
            'max_students.required' => 'Maximum students is required.',
            'max_students.min' => 'Maximum students must be at least 1.',
            'max_students.max' => 'Maximum students cannot exceed 100.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be scheduled, in_progress, completed, or cancelled.',
            'price_per_student.required' => 'Price per student is required.',
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'status' => $this->status ?? 'scheduled',
            'duration_minutes' => $this->duration_minutes ?? 120,
            'max_students' => $this->max_students ?? 20,
            'current_students' => 0,
            'price_per_student' => $this->price_per_student ?? 0,
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that instructor is available
            if ($this->instructor_id) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isAvailable()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not available.');
                }
            }
        });
    }
}
