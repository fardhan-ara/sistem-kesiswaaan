<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 5px; }
        h3 { text-align: center; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-center { text-align: center; }
        .header-info { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h2>LAPORAN KELAS</h2>
    <h3>{{ $kelas->nama_kelas }}</h3>
    
    <div class="header-info">
        <p><strong>Wali Kelas:</strong> {{ $kelas->waliKelas->nama_guru ?? '-' }}</p>
        <p><strong>Tahun Ajaran:</strong> {{ $kelas->tahunAjaran->nama ?? '-' }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th class="text-center">JK</th>
                <th class="text-center">Pelanggaran</th>
                <th class="text-center">Poin Pelanggaran</th>
                <th class="text-center">Prestasi</th>
                <th class="text-center">Poin Prestasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $siswa)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $siswa['nis'] }}</td>
                <td>{{ $siswa['nama'] }}</td>
                <td class="text-center">{{ $siswa['jenis_kelamin'] }}</td>
                <td class="text-center">{{ $siswa['pelanggaran'] }}</td>
                <td class="text-center">{{ $siswa['poin_pelanggaran'] }}</td>
                <td class="text-center">{{ $siswa['prestasi'] }}</td>
                <td class="text-center">{{ $siswa['poin_prestasi'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-center">TOTAL</th>
                <th class="text-center">{{ $data->sum('pelanggaran') }}</th>
                <th class="text-center">{{ $data->sum('poin_pelanggaran') }}</th>
                <th class="text-center">{{ $data->sum('prestasi') }}</th>
                <th class="text-center">{{ $data->sum('poin_prestasi') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
