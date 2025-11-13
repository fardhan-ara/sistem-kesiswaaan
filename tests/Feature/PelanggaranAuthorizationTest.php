<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\JenisPelanggaran;
use App\Models\Pelanggaran;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelanggaranAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected $pelanggaran;

    protected function setUp(): void
    {
        parent::setUp();
        
        $tahunAjaran = TahunAjaran::create(['nama_tahun' => '2024/2025', 'semester' => 'Ganjil', 'status' => 'aktif']);
        $kelas = Kelas::create(['nama_kelas' => 'X IPA 1', 'wali_kelas' => 'Pak Budi']);
        
        $userSiswa = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'users_id' => $userSiswa->id,
            'nis' => '12345',
            'nama_siswa' => 'Test Siswa',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);
        
        $userGuru = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'users_id' => $userGuru->id,
            'nip' => '67890',
            'nama_guru' => 'Test Guru'
        ]);
        
        $jenisPelanggaran = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Terlambat',
            'poin' => 10,
            'kategori' => 'ringan'
        ]);

        $this->pelanggaran = Pelanggaran::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'tanggal_pelanggaran' => '2024-01-15',
            'poin' => 10,
            'keterangan' => 'Test',
            'status_verifikasi' => 'pending'
        ]);
    }

    public function test_admin_can_verify_pelanggaran()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post("/pelanggaran/{$this->pelanggaran->id}/verify");

        $response->assertStatus(302);
    }

    public function test_kesiswaan_can_verify_pelanggaran()
    {
        $kesiswaan = User::factory()->create(['role' => 'kesiswaan']);

        $response = $this->actingAs($kesiswaan)->post("/pelanggaran/{$this->pelanggaran->id}/verify");

        $response->assertStatus(302);
    }

    public function test_guru_cannot_verify_pelanggaran()
    {
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($guru)->post("/pelanggaran/{$this->pelanggaran->id}/verify");

        $response->assertForbidden();
    }

    public function test_siswa_cannot_verify_pelanggaran()
    {
        $siswa = User::factory()->create(['role' => 'siswa']);

        $response = $this->actingAs($siswa)->post("/pelanggaran/{$this->pelanggaran->id}/verify");

        $response->assertForbidden();
    }

    public function test_ortu_cannot_verify_pelanggaran()
    {
        $ortu = User::factory()->create(['role' => 'ortu']);

        $response = $this->actingAs($ortu)->post("/pelanggaran/{$this->pelanggaran->id}/verify");

        $response->assertForbidden();
    }
}
