<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_cus'        => 'required',
            'email'           => 'nullable|email',
            'no_hp'           => 'required|numeric',
            'alamat'          => 'nullable',
            'tanggal_lahir'   => 'nullable|date',
            'jk'              => 'nullable',
            'agama'           => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_cus.required' => 'Nama customer wajib diisi',
            'no_hp.required'    => 'No HP wajib diisi',
        ];
    }
}