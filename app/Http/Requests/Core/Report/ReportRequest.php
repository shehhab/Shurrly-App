<?php

namespace App\Http\Requests\Core\Report;
use App\Http\Requests\Auth\Request;


class ReportRequest extends Request
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
            'report' => 'required|string',
            'report_to' => 'required|exists:seekers,id',
        ];
    }
}
