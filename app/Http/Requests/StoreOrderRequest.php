<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Guest & auth boleh checkout
    }

    public function rules(): array
    {
        return [
            'nama_pemesan'  => 'required|string|max:255',
            'no_hp'         => 'required|string|max:20',
            'event_date'    => 'required|date|after:today',
            'event_address' => 'required|string|max:500',
            'event_city'    => 'required|string|max:100',
            'distance_km'   => 'required|numeric|min:0|max:200',
            'notes'         => 'nullable|string|max:1000',
            'coupon_code'   => 'nullable|string|max:50',
            'custom_menus'              => 'nullable|array|max:5',
            'custom_menus.*.item_name'  => 'required_with:custom_menus|string|max:255',
            'custom_menus.*.description'=> 'nullable|string|max:500',
            'custom_menus.*.pax'        => 'required_with:custom_menus|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pemesan.required'  => 'Nama pemesan wajib diisi.',
            'no_hp.required'         => 'Nomor HP wajib diisi.',
            'event_date.required'    => 'Tanggal acara wajib diisi.',
            'event_date.after'       => 'Tanggal acara harus setelah hari ini.',
            'event_address.required' => 'Alamat acara wajib diisi.',
            'event_city.required'    => 'Kota acara wajib diisi.',
            'distance_km.required'   => 'Jarak pengiriman wajib diisi.',
            'distance_km.min'        => 'Jarak pengiriman tidak boleh negatif.',
        ];
    }
}
