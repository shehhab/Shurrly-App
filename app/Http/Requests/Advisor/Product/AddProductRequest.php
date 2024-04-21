<?php

namespace App\Http\Requests\Advisor\Product;

use App\Http\Requests\Auth\Request;

class AddProductRequest extends Request
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
            //'name' =>'required|max:255|string',
            'title' =>'required|max:255|string',
            'description' =>'required|max:255|string',
            'price' => 'required|numeric',
            'image'=>['required','image','mimes:jpg,jpeg,png,webp,gif','max:8000'],
            'product_pdf' => ['required_without:Product_Video', 'file', 'mimes:pdf', 'max:50480', 'prohibited_unless:Product_Video,null'],
            'Product_Video' => ['required_without:product_pdf', 'file', 'mimes:mov,mp4,mp3', 'max:50480', 'prohibited_unless:product_pdf,null'],
            'advisor_id' => 'required|exists:advisors,id', // Assuming advisor_id comes from the request
        'skills' => 'required|array|min:1',
        'skills.*' => 'string|distinct',  //Make sure you are not skilled
        ];


    }
    public function messages()
{
    return [
        'product_pdf.required_without' => '',
        'Product_Video.required_without' => '',
        'product_pdf.prohibited_unless' => 'the PDF file cannot be selected if a video file is submitted',
        'Product_Video.prohibited_unless' => 'the video file cannot be selected if a PDF file is submitted',
        'advisor_id.required' => 'Advisor ID is required.',
        'advisor_id.exists' => 'The selected advisor Not Found.'
    ];
}
}
