<?php

namespace App\Http\Requests\StudentTheoryEnrollment;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentTheoryEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\StudentTheoryEnrollment::class);
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
            'theory_class_id' => ['required', 'exists:theory_classes,id'],
            'status' => ['required', 'in:enrolled,attended,absent,cancelled'],
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
            'student_id.required' => 'Student is required.',
            'student_id.exists' => 'Selected student does not exist.',
            'theory_class_id.required' => 'Theory class is required.',
            'theory_class_id.exists' => 'Selected theory class does not exist.',
            'status.required' => 'Status is required.',
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'status' => $this->status ?? 'enrolled',
            'attended' => $this->boolean('attended', false),
            'enrolled_at' => $this->enrolled_at ?? now()->toDateString(),
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

            // Validate that theory class has available spots
            if ($this->theory_class_id) {
                $theoryClass = \App\Models\TheoryClass::find($this->theory_class_id);
                if ($theoryClass && !$theoryClass->hasAvailableSpots()) {
                    $validator->errors()->add('theory_class_id', 'Theory class is full.');
                }
            }

            // Validate that student is not already enrolled
            if ($this->student_id && $this->theory_class_id) {
                $existingEnrollment = \App\Models\StudentTheoryEnrollment::where('student_id', $this->student_id)
                    ->where('theory_class_id', $this->theory_class_id)
                    ->exists();
                
                if ($existingEnrollment) {
                    $validator->errors()->add('student_id', 'Student is already enrolled in this theory class.');
                }
            }
        });
    }
}
