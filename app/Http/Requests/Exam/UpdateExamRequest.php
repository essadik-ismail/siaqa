<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('exam'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $examId = $this->route('exam')->id;

        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'exam_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('exams', 'exam_number')->ignore($examId)
            ],
            'exam_type' => ['sometimes', 'in:theory,practical,simulation'],
            'license_category' => ['nullable', 'string', 'max:10'],
            'scheduled_at' => ['sometimes', 'date'],
            'completed_at' => ['nullable', 'date', 'after:scheduled_at'],
            'duration_minutes' => ['sometimes', 'integer', 'min:15', 'max:480'],
            'status' => ['sometimes', 'in:scheduled,in_progress,passed,failed,cancelled,no_show'],
            'location' => ['sometimes', 'string', 'max:200'],
            'exam_fee' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'max_score' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'exam_results' => ['nullable', 'array'],
            'examiner_notes' => ['nullable', 'string', 'max:1000'],
            'feedback' => ['nullable', 'string', 'max:1000'],
            'retake_date' => ['nullable', 'date', 'after:today'],
            'cancellation_reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_id.exists' => 'Selected student does not exist.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'exam_number.unique' => 'Exam number already exists.',
            'exam_type.in' => 'Exam type must be theory, practical, or simulation.',
            'scheduled_at.date' => 'Scheduled time must be a valid date.',
            'completed_at.after' => 'Completed time must be after scheduled time.',
            'duration_minutes.min' => 'Duration must be at least 15 minutes.',
            'duration_minutes.max' => 'Duration cannot exceed 8 hours.',
            'status.in' => 'Status must be scheduled, in_progress, passed, failed, cancelled, or no_show.',
            'exam_fee.min' => 'Exam fee cannot be negative.',
            'exam_fee.max' => 'Exam fee cannot exceed 999,999.99.',
            'score.min' => 'Score cannot be negative.',
            'score.max' => 'Score cannot exceed 100.',
            'max_score.min' => 'Maximum score must be at least 1.',
            'max_score.max' => 'Maximum score cannot exceed 100.',
            'retake_date.after' => 'Retake date must be in the future.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'instructor_id' => 'instructor',
            'exam_number' => 'exam number',
            'exam_type' => 'exam type',
            'license_category' => 'license category',
            'scheduled_at' => 'scheduled time',
            'completed_at' => 'completed time',
            'duration_minutes' => 'duration',
            'location' => 'location',
            'exam_fee' => 'exam fee',
            'score' => 'score',
            'max_score' => 'maximum score',
            'exam_results' => 'exam results',
            'examiner_notes' => 'examiner notes',
            'feedback' => 'feedback',
            'retake_date' => 'retake date',
            'cancellation_reason' => 'cancellation reason',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $exam = $this->route('exam');

            // Validate that student is active
            if ($this->has('student_id')) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that instructor is available for practical exams
            if ($this->has('exam_type') && $this->exam_type === 'practical' && $this->has('instructor_id')) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isAvailable()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not available.');
                }
            }

            // Validate that score is provided for completed exams
            if ($this->has('status') && in_array($this->status, ['passed', 'failed']) && !$this->score) {
                $validator->errors()->add('score', 'Score is required for completed exams.');
            }

            // Validate that retake date is provided for failed exams
            if ($this->has('status') && $this->status === 'failed' && !$this->retake_date) {
                $validator->errors()->add('retake_date', 'Retake date is required for failed exams.');
            }

            // Validate that completed exams cannot be modified
            if (in_array($exam->status, ['passed', 'failed']) && $this->has('status') && !in_array($this->status, ['passed', 'failed'])) {
                $validator->errors()->add('status', 'Completed exams cannot be modified.');
            }

            // Validate that cancelled exams cannot be modified
            if ($exam->isCancelled() && $this->has('status') && $this->status !== 'cancelled') {
                $validator->errors()->add('status', 'Cancelled exams cannot be modified.');
            }
        });
    }
}
