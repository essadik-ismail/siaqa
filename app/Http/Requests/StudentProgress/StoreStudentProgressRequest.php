<?php

namespace App\Http\Requests\StudentProgress;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentProgressRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'skill_category' => ['required', 'string', 'max:100'],
            'skill_name' => ['required', 'string', 'max:100'],
            'skill_level' => ['required', 'in:beginner,intermediate,advanced,expert'],
            'hours_practiced' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'attempts' => ['required', 'integer', 'min:0', 'max:1000'],
            'success_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'instructor_notes' => ['nullable', 'string', 'max:1000'],
            'assessment_criteria' => ['nullable', 'array'],
            'is_required' => ['boolean'],
            'is_completed' => ['boolean'],
            'last_practiced_at' => ['nullable', 'date', 'before_or_equal:today'],
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
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'instructor_id.required' => 'Instructor is required.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'skill_category.required' => 'Skill category is required.',
            'skill_name.required' => 'Skill name is required.',
            'skill_level.required' => 'Skill level is required.',
            'skill_level.in' => 'Skill level must be beginner, intermediate, advanced, or expert.',
            'hours_practiced.required' => 'Hours practiced is required.',
            'hours_practiced.min' => 'Hours practiced cannot be negative.',
            'hours_practiced.max' => 'Hours practiced cannot exceed 999.99.',
            'attempts.required' => 'Attempts is required.',
            'attempts.min' => 'Attempts cannot be negative.',
            'attempts.max' => 'Attempts cannot exceed 1000.',
            'success_rate.required' => 'Success rate is required.',
            'success_rate.min' => 'Success rate cannot be negative.',
            'success_rate.max' => 'Success rate cannot exceed 100.',
            'last_practiced_at.before_or_equal' => 'Last practiced date cannot be in the future.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'lesson_id' => 'lesson',
            'instructor_id' => 'instructor',
            'skill_category' => 'skill category',
            'skill_name' => 'skill name',
            'skill_level' => 'skill level',
            'hours_practiced' => 'hours practiced',
            'attempts' => 'attempts',
            'success_rate' => 'success rate',
            'instructor_notes' => 'instructor notes',
            'assessment_criteria' => 'assessment criteria',
            'is_required' => 'is required',
            'is_completed' => 'is completed',
            'last_practiced_at' => 'last practiced at',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'is_required' => $this->boolean('is_required', false),
            'is_completed' => $this->boolean('is_completed', false),
            'last_practiced_at' => $this->last_practiced_at ?? now()->toDateString(),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that student is active
            if ($this->student_id) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that instructor is active
            if ($this->instructor_id) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Validate that lesson belongs to the student if specified
            if ($this->lesson_id && $this->student_id) {
                $lesson = \App\Models\Lesson::find($this->lesson_id);
                if ($lesson && $lesson->student_id !== $this->student_id) {
                    $validator->errors()->add('lesson_id', 'Lesson does not belong to the selected student.');
                }
            }
        });
    }
}
