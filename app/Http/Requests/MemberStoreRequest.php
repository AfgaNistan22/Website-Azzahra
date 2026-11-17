<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cus'       => 'required|exists:customers,id_cus',
            'kode_member'  => 'required|unique:members,kode_member',
            'tgl_daftar'   => 'nullable|date',
            'poin'         => 'nullable|integer',
            'status'       => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'id_cus.required'      => 'Customer wajib dipilih',
            'id_cus.exists'        => 'Customer tidak ditemukan',
            'kode_member.required' => 'Kode member wajib diisi',
            'kode_member.unique'   => 'Kode member sudah digunakan',
            'status.required'      => 'Status wajib diisi',
        ];
    }
}