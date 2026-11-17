<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cus'       => 'required|exists:customers,id_cus',
            'kode_member'  => 'required',
            'tgl_daftar'   => 'nullable|date',
            'poin'         => 'nullable|integer',
            'status'       => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'id_cus.required'      => 'Customer wajib dipilih',
            'kode_member.required' => 'Kode member wajib diisi',
            'status.required'      => 'Status wajib diisi',
        ];
    }
}