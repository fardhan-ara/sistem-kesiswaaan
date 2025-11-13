<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan {{ $judul }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #000; padding-bottom: 10px; }
        .header img { width: 80px; height: 80px; }
        .header h2 { margin: 5px 0; font-size: 18px; }
        .header p { margin: 3px 0; font-size: 11px; }
        .info { margin-bottom: 20px; }
        .info table { width: 100%; }
        .info td { padding: 3px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data th, table.data td { border: 1px solid #000; padding: 8px; text-align: left; }
        table.data th { background-color: #f0f0f0; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; border-top: 1px solid #000; padding-top: 5px; }
        .ttd { margin-top: 40px; text-align: right; }
        .ttd p { margin: 5px 0; }
        .ttd .nama { margin-top: 60px; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==" alt="Logo">
        <h2>SISTEM INFORMASI KESISWAAN</h2>
        <h2>SMA NEGERI 1</h2>
        <p>Jl. Pendidikan No. 123, Telp. (021) 12345678</p>
        <p>Email: info@sman1.sch.id | Website: www.sman1.sch.id</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td width="20%">Judul Laporan</td>
                <td width="2%">:</td>
                <td>{{ $judul }}</td>
            </tr>
            @if(isset($filter_kelas))
            <tr>
                <td>Kelas</td>
                <td>:</td>
                <td>{{ $filter_kelas }}</td>
            </tr>
            @endif
            @if(isset($filter_tanggal))
            <tr>
                <td>Periode</td>
                <td>:</td>
                <td>{{ $filter_tanggal }}</td>
            </tr>
            @endif
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ date('d-m-Y H:i:s') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                @foreach($columns as $column)
                <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            <tr>
                @foreach($row as $cell)
                <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($columns) }}" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd">
        <p>{{ date('d F Y') }}</p>
        <p>Kepala Sekolah,</p>
        <p class="nama">Drs. H. Ahmad Suryadi, M.Pd</p>
        <p>NIP. 196512121990031005</p>
    </div>

    <div class="footer">
        <p>Laporan {{ $judul }} - Dicetak pada {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
