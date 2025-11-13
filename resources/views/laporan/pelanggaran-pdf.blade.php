<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pelanggaran</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN DATA PELANGGARAN SISWA</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis Pelanggaran</th>
                <th>Poin</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelanggarans as $index => $pelanggaran)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pelanggaran->siswa->nis }}</td>
                <td>{{ $pelanggaran->siswa->nama_siswa }}</td>
                <td>{{ $pelanggaran->siswa->kelas->nama_kelas }}</td>
                <td>{{ $pelanggaran->jenisPelanggaran->nama_pelanggaran }}</td>
                <td>{{ $pelanggaran->poin }}</td>
                <td>{{ $pelanggaran->status_verifikasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
