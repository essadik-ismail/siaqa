<?php

namespace App\Http\Requests\VehicleAssignment;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\VehicleAssignment::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['required', 'exists:vehicules,id'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'assigned_at' => ['required', 'date', 'before_or_equal:now'],
            'returned_at' => ['nullable', 'date', 'after:assigned_at'],
            'status' => ['required', 'in:assigned,in_use,returned,damaged'],
            'odometer_reading_start' => ['nullable', 'integer', 'min:0'],
            'odometer_reading_end' => ['nullable', 'integer', 'min:0', 'gte:odometer_reading_start'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vehicle_id.required' => 'Vehicle is required.',
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'instructor_id.required' => 'Instructor is required.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'assigned_at.required' => 'Assigned time is required.',
            'assigned_at.before_or_equal' => 'Assigned time cannot be in the future.',
            'returned_at.after' => 'Returned time must be after assigned time.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be assigned, in_use, returned, or damaged.',
            'odometer_reading_start.min' => 'Odometer reading start cannot be negative.',
            'odometer_reading_end.min' => 'Odometer reading end cannot be negative.',
            'odometer_reading_end.gte' => 'Odometer reading end must be greater than or equal to start reading.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'vehicle_id' => 'vehicle',
            'instructor_id' => 'instructor',
            'lesson_id' => 'lesson',
            'assigned_at' => 'assigned time',
            'returned_at' => 'returned time',
            'status' => 'status',
            'odometer_reading_start' => 'odometer reading start',
            'odometer_reading_end' => 'odometer reading end',
            'notes' => 'notes',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'tenant_id' => $this->user()->tenant_id,
            'assigned_at' => $this->assigned_at ?? now()->toDateTimeString(),
            'status' => $this->status ?? 'assigned',
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate that instructor is active
            if ($this->instructor_id) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Validate that vehicle is available for training
            if ($this->vehicle_id) {
                $vehicle = \App\Models\Vehicule::find($this->vehicle_id);
                if ($vehicle && !$vehicle->is_training_vehicle) {
                    $validator->errors()->add('vehicle_id', 'Selected vehicle is not available for training.');
                }
            }

            // Validate that lesson belongs to the instructor if specified
            if ($this->lesson_id && $this->instructor_id) {
                $lesson = \App\Models\Lesson::find($this->lesson_id);
                if ($lesson && $lesson->instructor_id !== $this->instructor_id) {
                    $validator->errors()->add('lesson_id', 'Lesson does not belong to the selected instructor.');
                }
            }

            // Validate that vehicle is not already assigned to another instructor
            if ($this->vehicle_id && $this->instructor_id) {
                $existingAssignment = \App\Models\VehicleAssignment::where('vehicle_id', $this->vehicle_id)
                    ->where('status', 'in_use')
                    ->where('instructor_id', '!=', $this->instructor_id)
                    ->exists();

                if ($existingAssignment) {
                    $validator->errors()->add('vehicle_id', 'Vehicle is already assigned to another instructor.');
                }
            }
        });
    }
}
