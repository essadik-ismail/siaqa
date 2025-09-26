<?php

namespace App\Http\Requests\Analytics;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnalyticsRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'before_or_equal:today'],
            'metric_name' => ['required', 'string', 'max:100'],
            'metric_value' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'dimensions' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'date.required' => 'Date is required.',
            'date.before_or_equal' => 'Date cannot be in the future.',
            'metric_name.required' => 'Metric name is required.',
            'metric_value.required' => 'Metric value is required.',
            'metric_value.min' => 'Metric value cannot be negative.',
            'metric_value.max' => 'Metric value cannot exceed 999,999,999.99.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'date' => 'date',
            'metric_name' => 'metric name',
            'metric_value' => 'metric value',
            'dimensions' => 'dimensions',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'date' => $this->date ?? now()->toDateString(),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that metric_name is one of the allowed values
            $allowedMetrics = [
                'total_students',
                'active_students',
                'new_students',
                'graduated_students',
                'total_instructors',
                'active_instructors',
                'total_lessons',
                'completed_lessons',
                'cancelled_lessons',
                'total_exams',
                'passed_exams',
                'failed_exams',
                'total_revenue',
                'monthly_revenue',
                'daily_revenue',
                'outstanding_payments',
                'vehicle_utilization',
                'instructor_utilization',
                'student_satisfaction',
                'exam_pass_rate',
            ];

            if (!in_array($this->metric_name, $allowedMetrics)) {
                $validator->errors()->add('metric_name', 'Invalid metric name. Must be one of: ' . implode(', ', $allowedMetrics));
            }
        });
    }
}
