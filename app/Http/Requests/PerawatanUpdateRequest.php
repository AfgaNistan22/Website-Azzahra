<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerawatanUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'max:255',
            ],

            'kategori_id' => [
                'required',
                'exists:kategori_perawatan,id',
            ],

            'deskripsi' => [
                'nullable',
                'string',
            ],

            'durasi' => [
                'required',
                'integer',
                'min:1',
            ],

            'harga' => [
                'required',
                'integer',
                'min:0',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama perawatan',
            'kategori_id' => 'kategori perawatan',
            'deskripsi' => 'deskripsi',
            'durasi' => 'durasi',
            'harga' => 'harga',
            'foto' => 'foto',
        ];
    }
}