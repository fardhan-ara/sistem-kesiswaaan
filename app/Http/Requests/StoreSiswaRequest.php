<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|unique:siswas,nis',
            'nama_siswa' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id'
        ];
    }
}
