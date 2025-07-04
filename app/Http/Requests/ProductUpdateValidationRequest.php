<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateValidationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'color' => 'nullable|array',
            'color.*' => 'string',

            'description' => 'required|string',
            'price' => 'required|numeric',
            'product_category_id' => 'required|exists:product_categories,id',

            'condition' => ['required', Rule::in(['old', 'new'])],
            'status' => ['nullable', Rule::in(['active', 'pending', 'sold', 'rejected'])],

            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
}
