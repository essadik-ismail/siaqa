<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Temporarily allow all users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'payment_number' => ['required', 'string', 'max:50', 'unique:payments,payment_number'],
            'payment_type' => ['required', 'in:lesson,exam,registration,package,refund'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'amount' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'amount_paid' => ['numeric', 'min:0', 'max:999999.99'],
            'payment_method' => ['required', 'in:cash,card,bank_transfer,check,online'],
            'status' => ['required', 'in:pending,partial,paid,overdue,cancelled'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
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
            'student_id.required' => 'Student is required.',
            'student_id.exists' => 'Selected student does not exist.',
            'payment_number.required' => 'Payment number is required.',
            'payment_number.unique' => 'Payment number already exists.',
            'payment_type.required' => 'Payment type is required.',
            'payment_type.in' => 'Payment type must be lesson, exam, registration, package, or refund.',
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'exam_id.exists' => 'Selected exam does not exist.',
            'amount.required' => 'Amount is required.',
            'amount.min' => 'Amount must be at least 0.01.',
            'amount.max' => 'Amount cannot exceed 999,999.99.',
            'amount_paid.min' => 'Amount paid cannot be negative.',
            'amount_paid.max' => 'Amount paid cannot exceed 999,999.99.',
            'amount_paid.custom' => 'Amount paid cannot exceed the total amount.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Payment method must be cash, card, bank_transfer, check, or online.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be pending, partial, paid, overdue, or cancelled.',
            'due_date.after_or_equal' => 'Due date must be today or in the future.',
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id ?? 1,
            'amount_paid' => $this->amount_paid ?? 0,
            'status' => $this->status ?? 'pending',
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that student is active
            if ($this->student_id) {
                $student = \App\Models\Student::find($this->student_id);
                if ($student && !$student->isActive()) {
                    $validator->errors()->add('student_id', 'Selected student is not active.');
                }
            }

            // Validate that lesson_id is provided for lesson payments
            if ($this->payment_type === 'lesson' && !$this->lesson_id) {
                $validator->errors()->add('lesson_id', 'Lesson is required for lesson payments.');
            }

            // Validate that exam_id is provided for exam payments
            if ($this->payment_type === 'exam' && !$this->exam_id) {
                $validator->errors()->add('exam_id', 'Exam is required for exam payments.');
            }

            // Validate that amount_paid does not exceed amount
            if ($this->amount_paid > $this->amount) {
                $validator->errors()->add('amount_paid', 'Amount paid cannot exceed the total amount.');
            }

            // Validate that balance is calculated correctly
            $balance = $this->amount - $this->amount_paid;
            if ($balance < 0) {
                $validator->errors()->add('amount_paid', 'Amount paid cannot exceed the total amount.');
            }

            // Validate that paid payments have paid_date
            if ($this->status === 'paid' && !$this->paid_date) {
                $validator->errors()->add('paid_date', 'Paid date is required for paid payments.');
            }

            // Validate that pending payments have due_date
            if ($this->status === 'pending' && !$this->due_date) {
                $validator->errors()->add('due_date', 'Due date is required for pending payments.');
            }
        });
    }
}
