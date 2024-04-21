<?php

namespace App\Http\Requests\Advisor\authantication;
use App\Http\Requests\Auth\Request;


class CreateAdvisorRequest extends Request
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
                'image'=>['required','image','mimes:jpg,jpeg,png,webp,gif','max:8000'],
                'certificates'=>['required', 'file', 'mimes:pdf', 'max:50480'],
                'offere'=>['required'],
                'bio' =>'required|max:255|string',
                'language' =>'required|string',
                'country' =>'required|string',
                'video' => ['required', 'file', 'mimes:mov,pdf,mp4,mp3', 'max:50480'],
                'available' => ['sometimes','boolean'],
                'category_id' => ['required', 'integer','exists:categories,id'],
                'session_duration' => ['required', 'date_format:H:i:s', function ($attribute, $value, $fail) {
                    $allowedDurations = ['00:15:00', '00:30:00', '00:45:00'];
                    if (!in_array($value, $allowedDurations)) {
                        $fail("The $attribute must be one of: " . implode(', ', $allowedDurations));
                    }
                }],
             ];
    }
}
