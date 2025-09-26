<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePackageRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'theory_hours' => ['sometimes', 'integer', 'min:0', 'max:1000'],
            'practical_hours' => ['sometimes', 'integer', 'min:0', 'max:1000'],
            'price' => ['sometimes', 'numeric', 'min:0.01', 'max:999999.99'],
            'validity_days' => ['sometimes', 'integer', 'min:1', 'max:3650'],
            'includes_exam' => ['boolean'],
            'includes_materials' => ['boolean'],
            'features' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'theory_hours.min' => 'Theory hours cannot be negative.',
            'theory_hours.max' => 'Theory hours cannot exceed 1000.',
            'practical_hours.min' => 'Practical hours cannot be negative.',
            'practical_hours.max' => 'Practical hours cannot exceed 1000.',
            'price.min' => 'Price must be at least 0.01.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'validity_days.min' => 'Validity days must be at least 1.',
            'validity_days.max' => 'Validity days cannot exceed 3650.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'package name',
            'description' => 'description',
            'license_category' => 'license category',
            'theory_hours' => 'theory hours',
            'practical_hours' => 'practical hours',
            'price' => 'price',
            'validity_days' => 'validity days',
            'includes_exam' => 'includes exam',
            'includes_materials' => 'includes materials',
            'features' => 'features',
            'is_active' => 'is active',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $package = $this->route('package');
            
            // Get current values or use provided values
            $theoryHours = $this->has('theory_hours') ? $this->theory_hours : $package->theory_hours;
            $practicalHours = $this->has('practical_hours') ? $this->practical_hours : $package->practical_hours;

            // Validate that at least one hour type is provided
            if ($theoryHours == 0 && $practicalHours == 0) {
                $validator->errors()->add('theory_hours', 'At least one hour type (theory or practical) must be provided.');
                $validator->errors()->add('practical_hours', 'At least one hour type (theory or practical) must be provided.');
            }
        });
    }
}
