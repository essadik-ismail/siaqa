<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('instructor'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $instructorId = $this->route('instructor')->id;

        return [
            'user_id' => ['sometimes', 'exists:users,id'],
            'employee_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('instructors', 'employee_number')->ignore($instructorId)
            ],
            'license_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('instructors', 'license_number')->ignore($instructorId)
            ],
            'license_expiry' => ['sometimes', 'date', 'after:today'],
            'license_categories' => ['nullable', 'string', 'max:100'],
            'years_experience' => ['sometimes', 'integer', 'min:0', 'max:50'],
            'hourly_rate' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'max_students' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'in:active,inactive,suspended'],
            'availability_schedule' => ['nullable', 'array'],
            'specializations' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_available' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'Selected user does not exist.',
            'employee_number.unique' => 'Employee number already exists.',
            'license_number.unique' => 'License number already exists.',
            'license_expiry.after' => 'License expiry date must be in the future.',
            'years_experience.min' => 'Years of experience cannot be negative.',
            'years_experience.max' => 'Years of experience cannot exceed 50.',
            'hourly_rate.min' => 'Hourly rate cannot be negative.',
            'hourly_rate.max' => 'Hourly rate cannot exceed 999,999.99.',
            'max_students.min' => 'Maximum students must be at least 1.',
            'max_students.max' => 'Maximum students cannot exceed 100.',
            'status.in' => 'Status must be active, inactive, or suspended.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'user account',
            'employee_number' => 'employee number',
            'license_number' => 'license number',
            'license_expiry' => 'license expiry date',
            'license_categories' => 'license categories',
            'years_experience' => 'years of experience',
            'hourly_rate' => 'hourly rate',
            'max_students' => 'maximum students',
            'availability_schedule' => 'availability schedule',
            'specializations' => 'specializations',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_available')) {
            $this->merge([
                'is_available' => $this->boolean('is_available'),
            ]);
        }
    }
}
