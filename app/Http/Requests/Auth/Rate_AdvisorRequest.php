<?php

namespace App\Http\Requests\Auth;
use App\Http\Requests\Auth\Request;


class Rate_AdvisorRequest extends Request
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
            'advisor_id' => ['required', 'integer'],
            'rate' => ['required', 'integer', 'min:1','max:5'],
            'seeker_id'=> ['required', 'integer', 'exists:seekers,id'],
            'message' => ['nullable', 'min:10']
        ];
    }
}
