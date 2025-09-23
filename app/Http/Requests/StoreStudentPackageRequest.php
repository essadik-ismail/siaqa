<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'package_id' => 'required|exists:packages,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:pending,active,completed,cancelled',
            'purchase_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:purchase_date',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Student is required',
            'student_id.exists' => 'Selected student does not exist',
            'package_id.required' => 'Package is required',
            'package_id.exists' => 'Selected package does not exist',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price must be a number',
            'price.min' => 'Price must be at least 0',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be one of: pending, active, completed, cancelled',
            'purchase_date.required' => 'Purchase date is required',
            'purchase_date.date' => 'Purchase date must be a valid date',
            'expiry_date.date' => 'Expiry date must be a valid date',
            'expiry_date.after' => 'Expiry date must be after purchase date',
            'notes.max' => 'Notes cannot exceed 1000 characters'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge(['status' => 'pending']);
        }

        // Set purchase date to today if not provided
        if (!$this->has('purchase_date')) {
            $this->merge(['purchase_date' => now()->toDateString()]);
        }
    }
}
