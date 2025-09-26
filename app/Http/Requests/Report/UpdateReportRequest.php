<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportRequest extends FormRequest
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
            'report_type' => ['sometimes', 'in:revenue,student_progress,instructor_performance,vehicle_usage,exam_results,custom'],
            'filters' => ['nullable', 'array'],
            'data' => ['nullable', 'array'],
            'status' => ['sometimes', 'in:generating,completed,failed'],
            'file_path' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'report_type.in' => 'Report type must be revenue, student_progress, instructor_performance, vehicle_usage, exam_results, or custom.',
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
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $report = $this->route('report');

            // Validate that filters are provided for custom reports
            if ($this->has('report_type') && $this->report_type === 'custom' && !$this->filters) {
                $validator->errors()->add('filters', 'Filters are required for custom reports.');
            }

            // Validate that file_path is provided for completed reports
            if ($this->has('status') && $this->status === 'completed' && !$this->file_path) {
                $validator->errors()->add('file_path', 'File path is required for completed reports.');
            }

            // Validate that completed reports cannot be modified
            if ($report->isCompleted() && $this->has('status') && $this->status !== 'completed') {
                $validator->errors()->add('status', 'Completed reports cannot be modified.');
            }

            // Validate that failed reports cannot be modified
            if ($report->isFailed() && $this->has('status') && $this->status !== 'failed') {
                $validator->errors()->add('status', 'Failed reports cannot be modified.');
            }
        });
    }
}
