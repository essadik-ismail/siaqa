<?php

namespace App\Http\Requests\Notification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNotificationRequest extends FormRequest
{


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
            'type' => ['sometimes', 'in:lesson_reminder,exam_reminder,payment_due,payment_received,lesson_cancelled,exam_cancelled,general,announcement'],
            'title' => ['sometimes', 'string', 'max:200'],
            'message' => ['sometimes', 'string', 'max:1000'],
            'data' => ['nullable', 'array'],
            'channel' => ['sometimes', 'in:email,sms,push,web'],
            'priority' => ['sometimes', 'in:low,normal,high,urgent'],
            'is_read' => ['boolean'],
            'scheduled_at' => ['nullable', 'date'],
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
            'type.in' => 'Invalid notification type.',
            'channel.in' => 'Channel must be email, sms, push, or web.',
            'priority.in' => 'Priority must be low, normal, high, or urgent.',
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
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $notification = $this->route('notification');

            // Validate that at least one recipient is specified
            $hasRecipient = $this->user_id || $this->student_id || $this->instructor_id;
            $hasExistingRecipient = $notification->user_id || $notification->student_id || $notification->instructor_id;
            
            if (!$hasRecipient && !$hasExistingRecipient) {
                $validator->errors()->add('user_id', 'At least one recipient (user, student, or instructor) must be specified.');
            }

            // Validate that student is active if specified
            if ($this->has('student_id') && $this->student_id) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that instructor is active if specified
            if ($this->has('instructor_id') && $this->instructor_id) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Validate that sent notifications cannot be modified
            if ($notification->sent_at && $this->has('type')) {
                $validator->errors()->add('type', 'Sent notifications cannot be modified.');
            }
        });
    }
}
