<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $judul }}</title>
    <style>
        /* Reset & Base */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
            color: #000;
        }
        
        /* Header Judul */
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            padding-bottom: 15px; 
            border-bottom: 2px solid #000;
        }
        .header h2 { 
            margin: 5px 0; 
            font-size: 16px; 
            font-weight: bold;
            text-transform: uppercase;
        }
        
        /* Info Laporan */
        .info { 
            margin: 15px 0 20px 0;
            background: #f9f9f9;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .info table { width: 100%; }
        .info td { 
            padding: 4px 5px; 
            font-size: 11px;
        }
        .info td:first-child { 
            width: 150px; 
            font-weight: bold;
        }
        .info td:nth-child(2) { width: 10px; }
        
        /* Tabel Data */
        table.data { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        table.data th { 
            background-color: #4a5568;
            color: white;
            border: 1px solid #2d3748; 
            padding: 8px 5px; 
            text-align: center;
            font-weight: bold;
            font-size: 10px;
        }
        table.data td { 
            border: 1px solid #cbd5e0; 
            padding: 6px 5px; 
            text-align: left;
            font-size: 10px;
        }
        table.data tbody tr:nth-child(even) {
            background-color: #f7fafc;
        }
        table.data tbody tr:hover {
            background-color: #edf2f7;
        }
        table.data td:first-child {
            text-align: center;
            font-weight: bold;
        }
        
        /* Summary */
        .summary {
            margin-top: 15px;
            padding: 10px;
            background: #edf2f7;
            border-left: 4px solid #4a5568;
        }
        .summary strong {
            font-size: 12px;
        }
        
        /* Tanda Tangan */
        .ttd { 
            margin-top: 40px; 
            text-align: right;
            page-break-inside: avoid;
        }
        .ttd p { 
            margin: 3px 0; 
            font-size: 11px;
        }
        .ttd .nama { 
            margin-top: 70px; 
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd .nip {
            font-size: 10px;
        }
        
        /* Footer */
        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            text-align: center; 
            font-size: 9px; 
            color: #666;
            border-top: 1px solid #ddd; 
            padding-top: 5px;
            background: white;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- JUDUL LAPORAN -->
    <div class="header">
        <h2>{{ $judul }}</h2>
    </div>

    <!-- INFORMASI LAPORAN -->
    <div class="info">
        <table>
            @if(isset($filter_kelas))
            <tr>
                <td>Filter Kelas</td>
                <td>:</td>
                <td>{{ $filter_kelas }}</td>
            </tr>
            @endif
            @if(isset($filter_tanggal))
            <tr>
                <td>Periode Tanggal</td>
                <td>:</td>
                <td>{{ $filter_tanggal }}</td>
            </tr>
            @endif
            <tr>
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y - HH:mm') }} WIB</td>
            </tr>
            @if(isset($total_data))
            <tr>
                <td>Total Data</td>
                <td>:</td>
                <td><strong>{{ $total_data }} record</strong></td>
            </tr>
            @endif
        </table>
    </div>

    <!-- TABEL DATA -->
    <table class="data">
        <thead>
            <tr>
                @foreach($columns as $column)
                <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $row)
            <tr>
                @foreach($row as $cell)
                <td>{{ $cell }}</td>
                @endforeach
            </tr>
            @empty
            <tr>
                <td colspan="{{ count($columns) }}" class="empty-state">
                    <strong>Tidak ada data yang ditampilkan</strong><br>
                    <small>Silakan ubah filter pencarian Anda</small>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- SUMMARY (jika ada data) -->
    @if(isset($total_data) && $total_data > 0)
    <div class="summary">
        <strong>Ringkasan:</strong> Laporan ini menampilkan {{ $total_data }} data 
        @if(isset($filter_kelas))
            dari kelas {{ $filter_kelas }}
        @endif
        @if(isset($filter_tanggal))
            pada periode {{ $filter_tanggal }}
        @endif
    </div>
    @endif

    <!-- TANDA TANGAN -->
    <div class="ttd">
        <p>{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        <p>Kepala Sekolah,</p>
        <p class="nama">Drs. H. Ahmad Suryadi, M.Pd</p>
        <p class="nip">NIP. 196512121990031005</p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>{{ $judul }} | Dicetak: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y HH:mm') }} WIB</p>
    </div>
</body>
</html>