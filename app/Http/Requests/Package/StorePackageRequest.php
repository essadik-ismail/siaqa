<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Package::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'theory_hours' => ['required', 'integer', 'min:0', 'max:1000'],
            'practical_hours' => ['required', 'integer', 'min:0', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'validity_days' => ['required', 'integer', 'min:1', 'max:3650'],
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
            'name.required' => 'Package name is required.',
            'theory_hours.required' => 'Theory hours is required.',
            'theory_hours.min' => 'Theory hours cannot be negative.',
            'theory_hours.max' => 'Theory hours cannot exceed 1000.',
            'practical_hours.required' => 'Practical hours is required.',
            'practical_hours.min' => 'Practical hours cannot be negative.',
            'practical_hours.max' => 'Practical hours cannot exceed 1000.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be at least 0.01.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'validity_days.required' => 'Validity days is required.',
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'theory_hours' => $this->theory_hours ?? 0,
            'practical_hours' => $this->practical_hours ?? 0,
            'validity_days' => $this->validity_days ?? 365,
            'includes_exam' => $this->boolean('includes_exam', false),
            'includes_materials' => $this->boolean('includes_materials', false),
            'is_active' => $this->boolean('is_active', true),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that at least one hour type is provided
            if ($this->theory_hours == 0 && $this->practical_hours == 0) {
                $validator->errors()->add('theory_hours', 'At least one hour type (theory or practical) must be provided.');
                $validator->errors()->add('practical_hours', 'At least one hour type (theory or practical) must be provided.');
            }
        });
    }
}
