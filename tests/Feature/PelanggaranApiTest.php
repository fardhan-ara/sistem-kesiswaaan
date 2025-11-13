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

class PelanggaranApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create(['role' => 'guru']);
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_post_pelanggaran_returns_201_and_saves_to_database()
    {
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

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->postJson('/api/v1/pelanggaran', [
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'tanggal_pelanggaran' => '2024-01-15',
            'keterangan' => 'Test keterangan',
            'poin' => $jenisPelanggaran->poin
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'siswa_id',
                    'guru_id',
                    'jenis_pelanggaran_id',
                    'poin',
                    'keterangan'
                ]
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Pelanggaran berhasil dibuat'
            ]);

        $this->assertDatabaseHas('pelanggarans', [
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'keterangan' => 'Test keterangan',
            'poin' => 10
        ]);
    }

    public function test_post_pelanggaran_without_token_returns_401()
    {
        $response = $this->postJson('/api/v1/pelanggaran', []);

        $response->assertStatus(401);
    }
}
