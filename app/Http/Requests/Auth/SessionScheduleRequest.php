<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\Auth\Request;

class SessionScheduleRequest extends Request
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
            //'seeker_id' => 'required|exists:seekers,id',
            'advisor_id' => 'required|exists:advisors,id',
            'session_date' => 'required|date_format:d/m/Y',
            'start_time' => 'required|date_format:h:i A',

            'note' => 'nullable|min:10',

        ];
    }
}
