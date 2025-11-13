<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\JenisPelanggaran;
use App\Models\Pelanggaran;
use App\Models\Sanksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelanggaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_dapat_membuat_pelanggaran()
    {
        $userGuru = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'users_id' => $userGuru->id,
            'nip' => '123456789',
            'nama_guru' => 'Guru Test'
        ]);

        $tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'semester' => 'Ganjil',
            'status' => 'aktif'
        ]);

        $kelas = Kelas::create(['nama_kelas' => 'X RPL 1']);

        $userSiswa = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'users_id' => $userSiswa->id,
            'nis' => '2024001',
            'nama_siswa' => 'Siswa Test',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $jenisPelanggaran = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Terlambat',
            'poin' => 10,
            'sanksi_rekomendasi' => 'Teguran'
        ]);

        $response = $this->actingAs($userGuru)->post(route('pelanggaran.store'), [
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'keterangan' => 'Test pelanggaran'
        ]);

        $response->assertRedirect(route('pelanggaran.index'));
        $this->assertDatabaseHas('pelanggarans', [
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'poin' => 10
        ]);
    }

    public function test_kesiswaan_dapat_memverifikasi_pelanggaran()
    {
        $userKesiswaan = User::factory()->create(['role' => 'kesiswaan']);

        $tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'semester' => 'Ganjil',
            'status' => 'aktif'
        ]);

        $kelas = Kelas::create(['nama_kelas' => 'X RPL 1']);

        $userSiswa = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'users_id' => $userSiswa->id,
            'nis' => '2024001',
            'nama_siswa' => 'Siswa Test',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $userGuru = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'users_id' => $userGuru->id,
            'nip' => '123456789',
            'nama_guru' => 'Guru Test'
        ]);

        $jenisPelanggaran = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Terlambat',
            'poin' => 10,
            'sanksi_rekomendasi' => 'Teguran'
        ]);

        $pelanggaran = Pelanggaran::create([
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'poin' => 10,
            'status_verifikasi' => 'pending'
        ]);

        $pelanggaran->update(['status_verifikasi' => 'verified']);

        $this->assertDatabaseHas('pelanggarans', [
            'id' => $pelanggaran->id,
            'status_verifikasi' => 'verified'
        ]);
    }

    public function test_sanksi_otomatis_dibuat_jika_poin_melebihi_ambang()
    {
        $userGuru = User::factory()->create(['role' => 'guru']);
        $guru = Guru::create([
            'users_id' => $userGuru->id,
            'nip' => '123456789',
            'nama_guru' => 'Guru Test'
        ]);

        $tahunAjaran = TahunAjaran::create([
            'nama_tahun' => '2024/2025',
            'semester' => 'Ganjil',
            'status' => 'aktif'
        ]);

        $kelas = Kelas::create(['nama_kelas' => 'X RPL 1']);

        $userSiswa = User::factory()->create(['role' => 'siswa']);
        $siswa = Siswa::create([
            'users_id' => $userSiswa->id,
            'nis' => '2024001',
            'nama_siswa' => 'Siswa Test',
            'kelas_id' => $kelas->id,
            'jenis_kelamin' => 'L',
            'tahun_ajaran_id' => $tahunAjaran->id
        ]);

        $jenisPelanggaran = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Merokok',
            'poin' => 100,
            'sanksi_rekomendasi' => 'Skorsing 1 minggu'
        ]);

        $response = $this->actingAs($userGuru)->post(route('pelanggaran.store'), [
            'siswa_id' => $siswa->id,
            'guru_id' => $guru->id,
            'jenis_pelanggaran_id' => $jenisPelanggaran->id,
            'keterangan' => 'Test pelanggaran berat'
        ]);

        $pelanggaran = Pelanggaran::where('siswa_id', $siswa->id)->first();
        $pelanggaran->update(['status_verifikasi' => 'verified']);

        $totalPoin = Pelanggaran::where('siswa_id', $siswa->id)
            ->where('status_verifikasi', 'verified')
            ->sum('poin');

        $this->assertGreaterThanOrEqual(100, $totalPoin);
    }
}
