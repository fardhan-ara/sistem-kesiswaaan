<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrestasiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'siswa' => [
                'id' => $this->siswa->id,
                'nis' => $this->siswa->nis,
                'nama' => $this->siswa->nama_siswa,
                'kelas' => $this->siswa->kelas->nama_kelas
            ],
            'jenis_prestasi' => [
                'id' => $this->jenisPrestasi->id,
                'nama' => $this->jenisPrestasi->nama_prestasi,
                'poin_reward' => $this->jenisPrestasi->poin_reward
            ],
            'keterangan' => $this->keterangan,
            'status_verifikasi' => $this->status_verifikasi,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
