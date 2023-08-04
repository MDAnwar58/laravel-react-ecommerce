<?php

namespace App\Http\Requests\Backend\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:50',
            'sub_category_id' => 'required',
            'title' => 'required|max:200',
            'color' => 'required|max:200',
            'price' => 'required|max:50',
            'discount' => 'required|max:50',
            'offer' => 'required|max:50',
            'des' => 'required',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif',
        ];
    }
}
