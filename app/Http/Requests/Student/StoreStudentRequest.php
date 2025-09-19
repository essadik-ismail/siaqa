<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Student::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'exists:users,id'],
            'student_number' => ['required', 'string', 'max:50', 'unique:students,student_number'],
            'name' => ['required', 'string', 'max:100'],
            'name_ar' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:students,email'],
            'phone' => ['required', 'string', 'max:20'],
            'cin' => ['required', 'string', 'max:20', 'unique:students,cin'],
            'birth_date' => ['required', 'date', 'before:today'],
            'birth_place' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:500'],
            'reference' => ['required', 'string', 'max:100'],
            'cinimage' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'emergency_contact_name' => ['required', 'string', 'max:100'],
            'emergency_contact_phone' => ['required', 'string', 'max:20'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'status' => ['required', 'in:registered,active,suspended,graduated,dropped'],
            'registration_date' => ['required', 'date'],
            'theory_hours_completed' => ['integer', 'min:0'],
            'practical_hours_completed' => ['integer', 'min:0'],
            'required_theory_hours' => ['integer', 'min:0', 'max:1000'],
            'required_practical_hours' => ['integer', 'min:0', 'max:1000'],
            'total_paid' => ['numeric', 'min:0'],
            'total_due' => ['numeric', 'min:0'],
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
            'student_number.required' => 'Student number is required.',
            'student_number.unique' => 'Student number already exists.',
            'name.required' => 'Name is required.',
            'name_ar.required' => 'Arabic name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'Email address already exists.',
            'phone.required' => 'Phone number is required.',
            'cin.required' => 'CIN number is required.',
            'cin.unique' => 'CIN number already exists.',
            'birth_date.required' => 'Birth date is required.',
            'birth_date.before' => 'Birth date must be in the past.',
            'birth_place.required' => 'Birth place is required.',
            'address.required' => 'Address is required.',
            'reference.required' => 'Reference is required.',
            'cinimage.image' => 'CIN image must be a valid image file.',
            'cinimage.mimes' => 'CIN image must be a JPEG, PNG, JPG, or GIF file.',
            'cinimage.max' => 'CIN image size cannot exceed 2MB.',
            'image.image' => 'Profile image must be a valid image file.',
            'image.mimes' => 'Profile image must be a JPEG, PNG, JPG, or GIF file.',
            'image.max' => 'Profile image size cannot exceed 2MB.',
            'emergency_contact_name.required' => 'Emergency contact name is required.',
            'emergency_contact_phone.required' => 'Emergency contact phone is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be registered, active, suspended, graduated, or dropped.',
            'registration_date.required' => 'Registration date is required.',
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

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'registration_date' => $this->registration_date ?? now()->toDateString(),
            'status' => $this->status ?? 'registered',
            'theory_hours_completed' => $this->theory_hours_completed ?? 0,
            'practical_hours_completed' => $this->practical_hours_completed ?? 0,
            'required_theory_hours' => $this->required_theory_hours ?? 20,
            'required_practical_hours' => $this->required_practical_hours ?? 20,
            'total_paid' => $this->total_paid ?? 0,
            'total_due' => $this->total_due ?? 0,
        ]);
    }
}
