<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelanggaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'guru_id' => 'required|exists:gurus,id',
            'jenis_pelanggaran_id' => 'required|exists:jenis_pelanggarans,id',
            'keterangan' => 'nullable|string|max:1000'
        ];
    }
}
