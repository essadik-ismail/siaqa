<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class StoreNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Notification::class);
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
            'student_id' => ['nullable', 'exists:students,id'],
            'instructor_id' => ['nullable', 'exists:instructors,id'],
            'type' => ['required', 'in:lesson_reminder,exam_reminder,payment_due,payment_received,lesson_cancelled,exam_cancelled,general,announcement'],
            'title' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:1000'],
            'data' => ['nullable', 'array'],
            'channel' => ['required', 'in:email,sms,push,web'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_read' => ['boolean'],
            'scheduled_at' => ['nullable', 'date', 'after:now'],
            'sent_at' => ['nullable', 'date', 'before_or_equal:now'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'Selected user does not exist.',
            'student_id.exists' => 'Selected student does not exist.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'type.required' => 'Notification type is required.',
            'type.in' => 'Invalid notification type.',
            'title.required' => 'Title is required.',
            'message.required' => 'Message is required.',
            'channel.required' => 'Channel is required.',
            'channel.in' => 'Channel must be email, sms, push, or web.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Priority must be low, normal, high, or urgent.',
            'scheduled_at.after' => 'Scheduled time must be in the future.',
            'sent_at.before_or_equal' => 'Sent time cannot be in the future.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'user',
            'student_id' => 'student',
            'instructor_id' => 'instructor',
            'type' => 'notification type',
            'title' => 'title',
            'message' => 'message',
            'data' => 'data',
            'channel' => 'channel',
            'priority' => 'priority',
            'is_read' => 'is read',
            'scheduled_at' => 'scheduled time',
            'sent_at' => 'sent time',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'is_read' => $this->boolean('is_read', false),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that at least one recipient is specified
            if (!$this->user_id && !$this->student_id && !$this->instructor_id) {
                $validator->errors()->add('user_id', 'At least one recipient (user, student, or instructor) must be specified.');
            }

            // Validate that student is active if specified
            if ($this->student_id) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that instructor is active if specified
            if ($this->instructor_id) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }
        });
    }
}
