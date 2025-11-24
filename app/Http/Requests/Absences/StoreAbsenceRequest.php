<?php

namespace App\Http\Requests\Absences;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Allow if user is creating for themselves or is a manager/HR creating for their team
        $user = auth()->user();
        $targetUserId = $this->input('user_id', $user->id);
        
        return $user->id === $targetUserId || 
               in_array($user->role, ['manager', 'hr']) || 
               $user->role === 'super_admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'sometimes',
                'string',
                'uuid',
                'exists:users,id'
            ],
            'type' => [
                'required',
                'string',
                // 'in:vacation,sick,personal,bereavement,maternity_paternity,unpaid,other'
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                'before_or_equal:end_date'
            ],
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date'
            ],
            'working_days' => [
                'required',
                'integer',
                'min:1',
                'max:365' // Reasonable upper limit
            ],
            'reason' => [
                'required',
                'string',
                'max:1000'
            ],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120' // 5MB
            ]
        ];
    }
    
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // If user_id is not provided, default to the authenticated user's ID
        if (!$this->has('user_id')) {
            $this->merge([
                'user_id' => auth()->id()
            ]);
        }
    }
}
