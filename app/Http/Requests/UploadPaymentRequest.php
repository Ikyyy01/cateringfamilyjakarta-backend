<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proof_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'method'      => 'nullable|in:qris,transfer,cash',
            'bank_name'   => 'required_if:method,transfer|nullable|string|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'proof_image.required' => 'Bukti pembayaran wajib diunggah.',
            'proof_image.image'    => 'File harus berupa gambar.',
            'proof_image.mimes'    => 'Format gambar harus JPG, JPEG, atau PNG.',
            'proof_image.max'      => 'Ukuran gambar maksimal 2MB.',
            'bank_name.required_if'=> 'Nama bank wajib diisi untuk metode transfer.',
        ];
    }
}
