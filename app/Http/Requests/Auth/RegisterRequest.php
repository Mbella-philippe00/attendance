<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name'   => ['required','string','max:100'],
            'last_name'    => ['required','string','max:100'],
            'email'        => ['required','email','max:255','unique:users,email'],
            'password'     => ['required','string','min:8'], // ajoute zxcvbn côté front pour la force
            'role'         => ['nullable','in:employee,manager,hr,super_admin,auditor'],
            'employee_id'  => ['required','string','max:20','unique:users,employee_id'],
            'site_id'      => ['nullable','uuid','exists:sites,id'],
            'phone'        => ['nullable','string','max:20'],
        ];
    }
}
