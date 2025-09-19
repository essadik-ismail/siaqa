<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('payment'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $paymentId = $this->route('payment')->id;

        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'payment_number' => [
                'sometimes', 
                'string', 
                'max:50', 
                Rule::unique('payments', 'payment_number')->ignore($paymentId)
            ],
            'payment_type' => ['sometimes', 'in:lesson,exam,registration,package,refund'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'amount' => ['sometimes', 'numeric', 'min:0.01', 'max:999999.99'],
            'amount_paid' => ['sometimes', 'numeric', 'min:0', 'max:999999.99'],
            'payment_method' => ['sometimes', 'in:cash,card,bank_transfer,check,online'],
            'status' => ['sometimes', 'in:pending,partial,paid,overdue,cancelled'],
            'due_date' => ['nullable', 'date'],
            'paid_date' => ['nullable', 'date', 'before_or_equal:today'],
            'transaction_id' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'payment_details' => ['nullable', 'array'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'student_id.exists' => 'Selected student does not exist.',
            'payment_number.unique' => 'Payment number already exists.',
            'payment_type.in' => 'Payment type must be lesson, exam, registration, package, or refund.',
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'exam_id.exists' => 'Selected exam does not exist.',
            'amount.min' => 'Amount must be at least 0.01.',
            'amount.max' => 'Amount cannot exceed 999,999.99.',
            'amount_paid.min' => 'Amount paid cannot be negative.',
            'amount_paid.max' => 'Amount paid cannot exceed 999,999.99.',
            'payment_method.in' => 'Payment method must be cash, card, bank_transfer, check, or online.',
            'status.in' => 'Status must be pending, partial, paid, overdue, or cancelled.',
            'paid_date.before_or_equal' => 'Paid date cannot be in the future.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'payment_number' => 'payment number',
            'payment_type' => 'payment type',
            'lesson_id' => 'lesson',
            'exam_id' => 'exam',
            'amount' => 'amount',
            'amount_paid' => 'amount paid',
            'payment_method' => 'payment method',
            'status' => 'status',
            'due_date' => 'due date',
            'paid_date' => 'paid date',
            'transaction_id' => 'transaction ID',
            'notes' => 'notes',
            'payment_details' => 'payment details',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $payment = $this->route('payment');

            // Validate that student is active
            if ($this->has('student_id')) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that lesson_id is provided for lesson payments
            if ($this->has('payment_type') && $this->payment_type === 'lesson' && !$this->lesson_id) {
                $validator->errors()->add('lesson_id', 'Lesson is required for lesson payments.');
            }

            // Validate that exam_id is provided for exam payments
            if ($this->has('payment_type') && $this->payment_type === 'exam' && !$this->exam_id) {
                $validator->errors()->add('exam_id', 'Exam is required for exam payments.');
            }

            // Validate that amount_paid does not exceed amount
            $amount = $this->has('amount') ? $this->amount : $payment->amount;
            $amountPaid = $this->has('amount_paid') ? $this->amount_paid : $payment->amount_paid;
            
            if ($amountPaid > $amount) {
                $validator->errors()->add('amount_paid', 'Amount paid cannot exceed the total amount.');
            }

            // Validate that paid payments have paid_date
            if ($this->has('status') && $this->status === 'paid' && !$this->paid_date) {
                $validator->errors()->add('paid_date', 'Paid date is required for paid payments.');
            }

            // Validate that pending payments have due_date
            if ($this->has('status') && $this->status === 'pending' && !$this->due_date) {
                $validator->errors()->add('due_date', 'Due date is required for pending payments.');
            }

            // Validate that cancelled payments cannot be modified
            if ($payment->isCancelled() && $this->has('status') && $this->status !== 'cancelled') {
                $validator->errors()->add('status', 'Cancelled payments cannot be modified.');
            }
        });
    }
}
