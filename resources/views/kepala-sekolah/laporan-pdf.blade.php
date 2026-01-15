<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Executive</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2 { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        table, th, td { border: 1px solid #ddd; }
        th { background: #667eea; color: white; padding: 8px; text-align: left; }
        td { padding: 6px; }
        .section { margin: 20px 0; }
        .section-title { background: #667eea; color: white; padding: 8px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN EXECUTIVE KEPALA SEKOLAH</h2>
        <p>Sistem Informasi Kesiswaan</p>
        <p>Periode: <strong>{{ strtoupper(str_replace('_', ' ', $report['periode'])) }}</strong></p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>
    
    <div class="section">
        <div class="section-title">RINGKASAN EKSEKUTIF</div>
        <table>
            <tr>
                <td width="50%"><strong>Total Pelanggaran</strong></td>
                <td>{{ $report['ringkasan']['total_pelanggaran'] }}</td>
            </tr>
            <tr>
                <td><strong>Total Prestasi</strong></td>
                <td>{{ $report['ringkasan']['total_prestasi'] }}</td>
            </tr>
            <tr>
                <td><strong>Total Sanksi</strong></td>
                <td>{{ $report['ringkasan']['total_sanksi'] }}</td>
            </tr>
            <tr>
                <td><strong>Siswa Terlibat</strong></td>
                <td>{{ $report['ringkasan']['siswa_terlibat'] }}</td>
            </tr>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">ANALYTICS - TOP 5 PELANGGARAN</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Pelanggaran</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report['analytics']['top_pelanggaran'] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_pelanggaran }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="section">
        <div class="section-title">ANALYTICS - TOP 5 KELAS</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Total Pelanggaran</th>
                </tr>
            </thead>
            <tbody>
                @foreach($report['analytics']['top_kelas'] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kelas }}</td>
                    <td>{{ $item->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if(count($report['insights']) > 0)
    <div class="section">
        <div class="section-title">INSIGHTS & REKOMENDASI</div>
        <ul>
            @foreach($report['insights'] as $insight)
                <li>{{ $insight }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div style="margin-top: 40px; text-align: right;">
        <p>Kepala Sekolah,</p>
        <br><br><br>
        <p>_______________________</p>
    </div>
</body>
</html>
