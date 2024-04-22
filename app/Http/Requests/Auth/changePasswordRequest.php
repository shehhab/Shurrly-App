<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rules;
use App\Http\Requests\Auth\Request;

class changePasswordRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'current_password' => 'required',
            'new_password' => ['required',Rules\Password::defaults(), new \App\Rules\StrongPassword

],            'confirm_password' => 'required|same:new_password',
        ];

    }
}
