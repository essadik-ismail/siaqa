<?php

namespace App\Http\Requests\StudentProgress;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProgressRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'instructor_id' => ['sometimes', 'exists:instructors,id'],
            'skill_category' => ['sometimes', 'string', 'max:100'],
            'skill_name' => ['sometimes', 'string', 'max:100'],
            'skill_level' => ['sometimes', 'in:beginner,intermediate,advanced,expert'],
            'hours_practiced' => ['sometimes', 'numeric', 'min:0', 'max:999.99'],
            'attempts' => ['sometimes', 'integer', 'min:0', 'max:1000'],
            'success_rate' => ['sometimes', 'numeric', 'min:0', 'max:100'],
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
            'student_id.exists' => 'Selected student does not exist.',
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'skill_level.in' => 'Skill level must be beginner, intermediate, advanced, or expert.',
            'hours_practiced.min' => 'Hours practiced cannot be negative.',
            'hours_practiced.max' => 'Hours practiced cannot exceed 999.99.',
            'attempts.min' => 'Attempts cannot be negative.',
            'attempts.max' => 'Attempts cannot exceed 1000.',
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
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $progress = $this->route('student_progress');

            // Validate that student is active
            if ($this->has('student_id')) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that instructor is active
            if ($this->has('instructor_id')) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Validate that lesson belongs to the student if specified
            if ($this->has('lesson_id') && $this->lesson_id) {
                $studentId = $this->has('student_id') ? $this->student_id : $progress->student_id;
                $lesson = \App\Models\Lesson::find($this->lesson_id);
                if ($lesson && $lesson->student_id !== $studentId) {
                    $validator->errors()->add('lesson_id', 'Lesson does not belong to the selected student.');
                }
            }
        });
    }
}
