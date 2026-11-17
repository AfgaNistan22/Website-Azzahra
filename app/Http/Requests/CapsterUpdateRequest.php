<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapsterUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_caps' => 'required',
            'no_hp'     => 'nullable|numeric',
            'tgl_lhr'   => 'required|date',
            'alamat'    => 'required',
            'jk'        => 'required',
            'agama'     => 'required',
            'status'    => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_caps.required' => 'Nama Capster wajib diisi',
            'tgl_lhr.required'   => 'Tanggal lahir wajib diisi',
            'alamat.required'    => 'Alamat wajib diisi',
            'jk.required'        => 'Jenis kelamin wajib diisi',
            'agama.required'     => 'Agama wajib diisi',
            'status.required'    => 'Status wajib diisi',
        ];
    }
}