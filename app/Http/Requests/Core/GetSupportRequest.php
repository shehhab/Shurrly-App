<?php

namespace App\Http\Requests\Core;

use App\Http\Requests\Auth\Request;
use Illuminate\Foundation\Http\FormRequest;

class GetSupportRequest extends Request
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email'=>['required','email','exists:seekers,email'],
            'message' => 'required|string',
        ];
    }


}
