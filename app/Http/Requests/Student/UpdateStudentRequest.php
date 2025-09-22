<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Temporarily allow all for testing
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentId = $this->route('student')->id;

        return [
            'tenant_id' => ['sometimes', 'integer', 'exists:tenants,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'student_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('students', 'student_number')->ignore($studentId)
            ],
            'name' => ['sometimes', 'string', 'max:100'],
            'name_ar' => ['sometimes', 'string', 'max:100'],
            'email' => [
                'sometimes', 
                'email', 
                'max:255', 
                Rule::unique('students', 'email')->ignore($studentId)
            ],
            'phone' => ['sometimes', 'string', 'max:20'],
            'cin' => [
                'sometimes', 
                'string', 
                'max:20', 
                Rule::unique('students', 'cin')->ignore($studentId)
            ],
            'birth_date' => ['sometimes', 'date', 'before:today'],
            'birth_place' => ['sometimes', 'string', 'max:100'],
            'address' => ['sometimes', 'string', 'max:500'],
            'reference' => ['sometimes', 'string', 'max:100'],
            'cinimage' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'emergency_contact_name' => ['sometimes', 'string', 'max:100'],
            'emergency_contact_phone' => ['sometimes', 'string', 'max:20'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'status' => ['sometimes', 'in:registered,active,suspended,graduated,dropped'],
            'registration_date' => ['sometimes', 'date'],
            'theory_hours_completed' => ['sometimes', 'integer', 'min:0'],
            'practical_hours_completed' => ['sometimes', 'integer', 'min:0'],
            'required_theory_hours' => ['sometimes', 'integer', 'min:0', 'max:1000'],
            'required_practical_hours' => ['sometimes', 'integer', 'min:0', 'max:1000'],
            'total_paid' => ['sometimes', 'numeric', 'min:0'],
            'total_due' => ['sometimes', 'numeric', 'min:0'],
            'progress_skills' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'Selected user does not exist.',
            'student_number.unique' => 'Student number already exists.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'Email address already exists.',
            'cin.unique' => 'CIN number already exists.',
            'birth_date.before' => 'Birth date must be in the past.',
            'cinimage.image' => 'CIN image must be a valid image file.',
            'cinimage.mimes' => 'CIN image must be a JPEG, PNG, JPG, or GIF file.',
            'cinimage.max' => 'CIN image size cannot exceed 2MB.',
            'image.image' => 'Profile image must be a valid image file.',
            'image.mimes' => 'Profile image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'Profile image size cannot exceed 2MB.',
            'status.in' => 'Status must be registered, active, suspended, graduated, or dropped.',
            'theory_hours_completed.min' => 'Theory hours completed cannot be negative.',
            'practical_hours_completed.min' => 'Practical hours completed cannot be negative.',
            'required_theory_hours.min' => 'Required theory hours cannot be negative.',
            'required_theory_hours.max' => 'Required theory hours cannot exceed 1000.',
            'required_practical_hours.min' => 'Required practical hours cannot be negative.',
            'required_practical_hours.max' => 'Required practical hours cannot exceed 1000.',
            'total_paid.min' => 'Total paid cannot be negative.',
            'total_due.min' => 'Total due cannot be negative.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'user account',
            'student_number' => 'student number',
            'name' => 'name',
            'name_ar' => 'Arabic name',
            'email' => 'email address',
            'phone' => 'phone number',
            'cin' => 'CIN number',
            'birth_date' => 'birth date',
            'birth_place' => 'birth place',
            'address' => 'address',
            'reference' => 'reference',
            'cinimage' => 'CIN image',
            'image' => 'profile image',
            'emergency_contact_name' => 'emergency contact name',
            'emergency_contact_phone' => 'emergency contact phone',
            'license_category' => 'license category',
            'registration_date' => 'registration date',
            'theory_hours_completed' => 'theory hours completed',
            'practical_hours_completed' => 'practical hours completed',
            'required_theory_hours' => 'required theory hours',
            'required_practical_hours' => 'required practical hours',
            'total_paid' => 'total paid',
            'total_due' => 'total due',
            'progress_skills' => 'progress skills',
        ];
    }
}
