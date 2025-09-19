<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Report::class);
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
            'report_type' => ['required', 'in:revenue,student_progress,instructor_performance,vehicle_usage,exam_results,custom'],
            'filters' => ['nullable', 'array'],
            'data' => ['nullable', 'array'],
            'status' => ['required', 'in:generating,completed,failed'],
            'file_path' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Report name is required.',
            'report_type.required' => 'Report type is required.',
            'report_type.in' => 'Report type must be revenue, student_progress, instructor_performance, vehicle_usage, exam_results, or custom.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be generating, completed, or failed.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'report name',
            'description' => 'description',
            'report_type' => 'report type',
            'filters' => 'filters',
            'data' => 'data',
            'status' => 'status',
            'file_path' => 'file path',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'created_by' => $this->user()->id,
            'status' => $this->status ?? 'generating',
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that filters are provided for custom reports
            if ($this->report_type === 'custom' && !$this->filters) {
                $validator->errors()->add('filters', 'Filters are required for custom reports.');
            }

            // Validate that file_path is provided for completed reports
            if ($this->status === 'completed' && !$this->file_path) {
                $validator->errors()->add('file_path', 'File path is required for completed reports.');
            }
        });
    }
}
