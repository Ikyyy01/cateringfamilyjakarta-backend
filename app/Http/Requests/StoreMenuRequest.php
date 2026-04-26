<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price'       => 'required|numeric|min:0',
            'min_pax'     => 'required|integer|min:1',
            'max_pax'     => 'required|integer|min:1|gte:min_pax',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak ditemukan.',
            'name.required'        => 'Nama menu wajib diisi.',
            'price.required'       => 'Harga wajib diisi.',
            'price.min'            => 'Harga tidak boleh negatif.',
            'min_pax.required'     => 'Minimum porsi wajib diisi.',
            'max_pax.gte'          => 'Maximum porsi harus lebih besar atau sama dengan minimum porsi.',
            'image.image'          => 'File harus berupa gambar.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
