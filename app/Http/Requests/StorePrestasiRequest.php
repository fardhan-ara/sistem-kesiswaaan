<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePrestasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => 'required|exists:siswas,id',
            'jenis_prestasi_id' => 'required|exists:jenis_prestasis,id',
            'keterangan' => 'nullable|string|max:500'
        ];
    }
}
