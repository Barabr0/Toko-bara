<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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

            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($this->route('product'))
            ],
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

            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['integer', 'exists:product_images,id'],

            'primary_image' => ['nullable', 'integer', 'exists:product_images,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Nama produk harus diisi",
            'name.unique' => "Nama produk sudah digunakan",
            'category_id.required' => "Kategori harus dipilih",
            'category_id.exists' => "Kategori tidak valid",
            'price.required' => "Harga harus diisi",
            'price.numeric' => "Harga harus berupa angka",
            'price.min' => "Harga minimal Rp 1.000",
            'discount_price.numeric' => "Harga diskon harus berupa angka",
            'discount_price.min' => "Harga diskon tidak boleh negatif",
            'discount_price.lt' => "Harga diskon harus lebih kecil dari harga normal",
            'stock.required' => "Stok harus diisi",
            'stock.integer' => "Stok harus berupa angka bulat",
            'stock.min' => "Stok tidak boleh negatif",
            'weight.required' => "Berat harus diisi",
            'weight.integer' => "Berat harus berupa angka bulat",
            'weight.min' => "Berat minimal 1 gram",
            'images.array' => "Gambar harus berupa array file",
            'images.max' => "Maksimal 10 gambar",
            'images.*.image' => "File harus berupa gambar",
            'images.*.mimes' => "Format gambar harus jpg, png, atau webp",
            'images.*.max' => "Ukuran gambar maksimal 2MB",
            'delete_images.*.exists' => "Gambar yang akan dihapus tidak ditemukan",
            'primary_image.exists' => "Gambar utama tidak ditemukan",
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
