<?php

namespace App\Http\Requests\Absences;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAbsenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        
        // $absence = $this->route('absences');
        // Only allow updates if the absence is still pending
        // if ($absence->status !== 'pending') {
        //     return false;
        // }

        // Allow if user is the owner, or is manager/hr/super_admin
        // $user = auth()->user();
        // return $user->id === $absence->user_id || 
        //     in_array($user->role, ['manager', 'hr']) || 
        //     $user->role === 'super_admin';
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'sometimes',
                'string',
                'in:vacation,sick,personal,bereavement,maternity_paternity,unpaid,other'
            ],
            'start_date' => [
                'sometimes',
                'date',
                'before_or_equal:end_date'
            ],
            'end_date' => [
                'sometimes',
                'date',
                'after_or_equal:start_date'
            ],
            'working_days' => [
                'sometimes',
                'integer',
                'min:1',
                'max:365'
            ],
            'reason' => [
                'sometimes',
                'string',
                'max:1000'
            ],
            'status' => [
                'sometimes',
                'string',
                'in:pending,approved,rejected',
                function ($attribute, $value, $fail) {
                    // Only allow status changes if user is manager/hr/admin
                    if (!in_array(auth()->user()->role, ['manager', 'hr', 'super_admin'])) {
                        $fail('You are not authorized to change the status.');
                    }
                }
            ],
            'rejection_reason' => [
                'required_if:status,rejected',
                'string',
                'max:500',
                'nullable'
            ],
            'attachment' => [
                'sometimes',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120' // 5MB
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'rejection_reason.required_if' => 'A rejection reason is required when rejecting an absence.',
            'status.in' => 'The selected status is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // If status is being updated to approved/rejected, set the approved_by and approved_at fields
        if ($this->has('status') && in_array($this->status, ['approved', 'rejected'])) {
            $this->merge([
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
        }
    }
}