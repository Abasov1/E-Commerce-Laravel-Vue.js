<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'category_id' => 'required',
            'merchant_id' => 'required',
            'brand_id' => 'required',
            'az_name' => 'required',
            'en_name' => 'required',
            'ru_name' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'az_inf' => 'required',
            'en_inf' => 'required',
            'ru_inf' => 'required'
        ];
    }
}
