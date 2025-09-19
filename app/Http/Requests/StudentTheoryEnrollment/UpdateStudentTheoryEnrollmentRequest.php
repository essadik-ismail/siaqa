<?php

namespace App\Http\Requests\StudentTheoryEnrollment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentTheoryEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('student_theory_enrollment'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'theory_class_id' => ['sometimes', 'exists:theory_classes,id'],
            'status' => ['sometimes', 'in:enrolled,attended,absent,cancelled'],
            'attended' => ['boolean'],
            'enrolled_at' => ['nullable', 'date'],
            'attended_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_id.exists' => 'Selected student does not exist.',
            'theory_class_id.exists' => 'Selected theory class does not exist.',
            'status.in' => 'Status must be enrolled, attended, absent, or cancelled.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'theory_class_id' => 'theory class',
            'status' => 'status',
            'attended' => 'attended',
            'enrolled_at' => 'enrolled at',
            'attended_at' => 'attended at',
            'notes' => 'notes',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $enrollment = $this->route('student_theory_enrollment');

            // Validate that student is active
            if ($this->has('student_id')) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that theory class has available spots (if changing class)
            if ($this->has('theory_class_id') && $this->theory_class_id !== $enrollment->theory_class_id) {
                $theoryClass = \App\Models\TheoryClass::find($this->theory_class_id);
                if ($theoryClass && !$theoryClass->hasAvailableSpots()) {
                    $validator->errors()->add('theory_class_id', 'Theory class is full.');
                }
            }

            // Validate that student is not already enrolled in the new class
            if ($this->has('student_id') && $this->has('theory_class_id')) {
                $existingEnrollment = \App\Models\StudentTheoryEnrollment::where('student_id', $this->student_id)
                    ->where('theory_class_id', $this->theory_class_id)
                    ->where('id', '!=', $enrollment->id)
                    ->exists();
                
                if ($existingEnrollment) {
                    $validator->errors()->add('student_id', 'Student is already enrolled in this theory class.');
                }
            }

            // Validate that attended_at is set when attended is true
            if ($this->boolean('attended') && !$this->attended_at) {
                $validator->errors()->add('attended_at', 'Attended date is required when marking as attended.');
            }
        });
    }
}
