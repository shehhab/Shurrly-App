<?php

namespace App\Http\Requests\Advisor\authantication;

use App\Http\Requests\Auth\Request;


class UpdateProfileAdvisorRequest extends Request
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
                'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8000'],
                'date_birth' => ['sometimes', 'date_format:d/m/Y', 'before_or_equal:' . now()->subYears(16)->format('d/m/Y'), 'after_or_equal:' . now()->subYears(70)->format('d/m/Y')],
                'certificates' => ['sometimes', 'file', 'mimes:pdf', 'max:50480'],
                'offer' => ['sometimes'],
                'bio' => 'sometimes|max:255|string',
                'video' => ['sometimes', 'file', 'mimes:video/quicktime,mp4,mp3', 'max:50480'],

                'days' => ['sometimes', 'array'],
                'days.*.day' => 'sometimes|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
                'days.*.from' => 'sometimes|date_format:H:i',
                'days.*.to' => 'sometimes|date_format:H:i|after:days.*.from',
                'days.*.break_to' => 'nullable|date_format:H:i|required_with:days.*.break_from',
                'days.*.break_from' => 'nullable|date_format:H:i|required_with:days.*.break_to',
            ];
        }

        public function messages()
        {
            return [
                'days.*.break_from.required_with' => 'If Break To is selected, Break From field must also be specified',
                'days.*.break_to.required_with' => 'If Break From is selected, Break To field must also be specified',
            ];
        }
}
