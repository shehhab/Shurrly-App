<?php

namespace App\Http\Requests\Core\Block;
use App\Http\Requests\Auth\Request;

class BlockRequest extends Request
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
            'blocked_user_id' => 'required',
        ];
    }
}
