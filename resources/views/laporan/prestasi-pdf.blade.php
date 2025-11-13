<!DOCTYPE html>
<html>
<head>
    <title>Laporan Prestasi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>LAPORAN DATA PRESTASI SISWA</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Jenis Prestasi</th>
                <th>Poin Reward</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prestasis as $index => $prestasi)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $prestasi->siswa->nis }}</td>
                <td>{{ $prestasi->siswa->nama_siswa }}</td>
                <td>{{ $prestasi->siswa->kelas->nama_kelas }}</td>
                <td>{{ $prestasi->jenisPrestasi->nama_prestasi }}</td>
                <td>{{ $prestasi->jenisPrestasi->poin_reward }}</td>
                <td>{{ $prestasi->status_verifikasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
