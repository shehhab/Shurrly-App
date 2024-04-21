<?php

namespace App\Http\Requests\Auth;

use App\Traits\AuthResponse;
use App\Http\Requests\Auth\Request;

class ValidOTPRequest extends Request
{
      /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  true ;
    }
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required',
            'otp'=>['required','max:4'],
        ];
    }



}
