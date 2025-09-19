<?php

namespace App\Http\Requests\VehicleAssignment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVehicleAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('vehicle_assignment'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => ['sometimes', 'exists:vehicules,id'],
            'instructor_id' => ['sometimes', 'exists:instructors,id'],
            'lesson_id' => ['nullable', 'exists:lessons,id'],
            'assigned_at' => ['sometimes', 'date'],
            'returned_at' => ['nullable', 'date', 'after:assigned_at'],
            'status' => ['sometimes', 'in:assigned,in_use,returned,damaged'],
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
            'vehicle_id.exists' => 'Selected vehicle does not exist.',
            'instructor_id.exists' => 'Selected instructor does not exist.',
            'lesson_id.exists' => 'Selected lesson does not exist.',
            'returned_at.after' => 'Returned time must be after assigned time.',
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
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $assignment = $this->route('vehicle_assignment');

            // Validate that instructor is active
            if ($this->has('instructor_id')) {
                $instructor = \App\Models\Instructor::find($this->instructor_id);
                if ($instructor && !$instructor->isActive()) {
                    $validator->errors()->add('instructor_id', 'Selected instructor is not active.');
                }
            }

            // Validate that vehicle is available for training
            if ($this->has('vehicle_id')) {
                $vehicle = \App\Models\Vehicule::find($this->vehicle_id);
                if ($vehicle && !$vehicle->is_training_vehicle) {
                    $validator->errors()->add('vehicle_id', 'Selected vehicle is not available for training.');
                }
            }

            // Validate that lesson belongs to the instructor if specified
            if ($this->has('lesson_id') && $this->lesson_id) {
                $instructorId = $this->has('instructor_id') ? $this->instructor_id : $assignment->instructor_id;
                $lesson = \App\Models\Lesson::find($this->lesson_id);
                if ($lesson && $lesson->instructor_id !== $instructorId) {
                    $validator->errors()->add('lesson_id', 'Lesson does not belong to the selected instructor.');
                }
            }

            // Validate that vehicle is not already assigned to another instructor
            if ($this->has('vehicle_id') && $this->has('instructor_id')) {
                $existingAssignment = \App\Models\VehicleAssignment::where('vehicle_id', $this->vehicle_id)
                    ->where('status', 'in_use')
                    ->where('instructor_id', '!=', $this->instructor_id)
                    ->where('id', '!=', $assignment->id)
                    ->exists();

                if ($existingAssignment) {
                    $validator->errors()->add('vehicle_id', 'Vehicle is already assigned to another instructor.');
                }
            }

            // Validate that returned assignments cannot be modified
            if ($assignment->status === 'returned' && $this->has('status') && $this->status !== 'returned') {
                $validator->errors()->add('status', 'Returned assignments cannot be modified.');
            }
        });
    }
}
