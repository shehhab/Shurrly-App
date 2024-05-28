<?php

namespace App\Http\Requests\Advisor\Product;

use App\Http\Requests\Auth\Request;
use Illuminate\Foundation\Http\FormRequest;

class EditeProductRequest extends Request
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
            'product_id' => 'required|integer' ,
            'title' =>'sometimes|max:255|string',
            'description' =>'sometimes|max:255|string',
            'price' => 'sometimes|numeric',
            'image'=>['sometimes','image','mimes:jpg,jpeg,png,webp,gif','max:8000'],
        ];

    }
}
