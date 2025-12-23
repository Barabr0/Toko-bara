<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
             'category_id' => ['required', 'exists:categories,id'],

            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],

            'price' => ['required', 'numeric', 'min:1000'],

            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],

            'stock' => ['required', 'integer', 'min:0'],
            'weight' => ['required', 'integer', 'min:1'],

            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],

            'images' => ['nullable', 'array', 'max:10'],

            'images.*' => [
                'image',
                'mimes:jpg,png,webp',
                'max:2048'
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "nama harus di isi",
            'category_id.required' => "nama harus di isi",
            'price.required' => "nama harus di isi",
            'discount_price.required' => "nama harus di isi",
            'stock.required' => "nama harus di isi",
            'weight.required' => "nama harus di isi",
            'images.required' => "nama harus di isi",
            'description.required' => "nama harus di isi",
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
