<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Create new PhpWord object
$phpWord = new PhpWord();

// Set document properties
$properties = $phpWord->getDocInfo();
$properties->setCreator('SIKAP System');
$properties->setTitle('Panduan Pengguna Lengkap - Sistem Kesiswaan (SIKAP)');
$properties->setDescription('Panduan lengkap penggunaan sistem informasi kesiswaan dan prestasi');

// Define styles
$phpWord->addTitleStyle(1, array('size' => 18, 'bold' => true, 'color' => '2E74B5'));
$phpWord->addTitleStyle(2, array('size' => 14, 'bold' => true, 'color' => '1F4E79'));
$phpWord->addTitleStyle(3, array('size' => 12, 'bold' => true, 'color' => '365F91'));

// Add section
$section = $phpWord->addSection();

// Title
$section->addTitle('PANDUAN PENGGUNA LENGKAP', 1);
$section->addTitle('SISTEM KESISWAAN (SIKAP)', 1);
$section->addTextBreak(2);

$section->addText('Sistem Informasi Kesiswaan dan Prestasi', array('bold' => true, 'size' => 14));
$section->addText('Panduan Lengkap untuk Semua Pengguna', array('italic' => true, 'size' => 12));
$section->addTextBreak(2);

// Table of Contents
$section->addTitle('DAFTAR ISI', 2);
$section->addTextBreak(1);

$toc = array(
    '1. Pengenalan Sistem',
    '2. Cara Mengakses Sistem', 
    '3. Panduan Login',
    '4. Dashboard Utama',
    '5. Panduan untuk Administrator',
    '6. Panduan untuk Staff Kesiswaan',
    '7. Panduan untuk Guru',
    '8. Panduan untuk Wali Kelas',
    '9. Panduan untuk Guru BK',
    '10. Panduan untuk Siswa',
    '11. Panduan untuk Orang Tua',
    '12. Fitur Laporan',
    '13. Troubleshooting',
    '14. FAQ'
);

foreach($toc as $item) {
    $section->addText($item, array('size' => 11));
}
$section->addPageBreak();

// 1. PENGENALAN SISTEM
$section->addTitle('1. PENGENALAN SISTEM', 2);
$section->addTextBreak(1);

$section->addTitle('1.1 Apa itu SIKAP?', 3);
$section->addText('SIKAP (Sistem Informasi Kesiswaan dan Prestasi) adalah aplikasi berbasis web yang dirancang khusus untuk mengelola seluruh aspek kesiswaan di sekolah.');
$section->addTextBreak(1);

$section->addText('Sistem ini membantu sekolah dalam:', array('bold' => true));
$benefits = array(
    'Mengelola data siswa secara terpusat dan terorganisir',
    'Mencatat pelanggaran dengan sistem poin otomatis',
    'Mencatat prestasi siswa dengan berbagai tingkatan',
    'Mengelola sanksi yang diberikan secara otomatis',
    'Melakukan bimbingan konseling dengan dokumentasi lengkap',
    'Berkomunikasi dengan orang tua secara real-time',
    'Membuat laporan dalam format PDF'
);

foreach($benefits as $benefit) {
    $section->addText('• ' . $benefit);
}
$section->addTextBreak(1);

$section->addTitle('1.2 Keunggulan Sistem', 3);
$advantages = array(
    'Otomatisasi Sanksi: Sanksi dibuat otomatis ketika poin pelanggaran mencapai 100',
    'Verifikasi Berlapis: Setiap data melalui proses verifikasi untuk akurasi',
    'Komunikasi Real-time: Orang tua dapat memantau anak secara langsung',
    'Laporan Lengkap: Export laporan dalam format PDF dengan filter',
    'Multi-Role: Mendukung 7 jenis pengguna dengan hak akses berbeda',
    'Responsive: Dapat diakses dari komputer, tablet, dan smartphone'
);

foreach($advantages as $advantage) {
    $section->addText('✓ ' . $advantage);
}
$section->addTextBreak(1);

$section->addTitle('1.3 Jenis Pengguna', 3);

// Create table for user roles
$tableStyle = array(
    'borderSize' => 6,
    'borderColor' => '999999',
    'cellMargin' => 80
);
$phpWord->addTableStyle('userRoles', $tableStyle);
$table = $section->addTable('userRoles');

// Header row
$table->addRow();
$table->addCell(800)->addText('No', array('bold' => true));
$table->addCell(2500)->addText('Role', array('bold' => true));
$table->addCell(3000)->addText('Deskripsi', array('bold' => true));
$table->addCell(3000)->addText('Hak Akses', array('bold' => true));

// Data rows
$roles = array(
    array('1', 'Administrator', 'Pengelola utama sistem', 'Akses penuh semua fitur'),
    array('2', 'Staff Kesiswaan', 'Pengelola data kesiswaan', 'Hampir sama dengan admin'),
    array('3', 'Guru', 'Pengajar di sekolah', 'Input pelanggaran dan prestasi'),
    array('4', 'Wali Kelas', 'Guru yang mengelola kelas', 'Kelola kelas dan komunikasi ortu'),
    array('5', 'Guru BK', 'Guru Bimbingan Konseling', 'Bimbingan konseling siswa'),
    array('6', 'Siswa', 'Peserta didik', 'Lihat data pribadi'),
    array('7', 'Orang Tua', 'Wali siswa', 'Monitor data anak')
);

foreach($roles as $role) {
    $table->addRow();
    $table->addCell(800)->addText($role[0]);
    $table->addCell(2500)->addText($role[1], array('bold' => true));
    $table->addCell(3000)->addText($role[2]);
    $table->addCell(3000)->addText($role[3]);
}
$section->addPageBreak();

// 2. CARA MENGAKSES SISTEM
$section->addTitle('2. CARA MENGAKSES SISTEM', 2);
$section->addTextBreak(1);

$section->addTitle('2.1 Persyaratan Sistem', 3);
$section->addText('Perangkat yang Didukung:', array('bold' => true));
$devices = array(
    'Komputer/Laptop (Windows, Mac, Linux)',
    'Tablet (Android, iOS)',
    'Smartphone (Android, iOS)'
);
foreach($devices as $device) {
    $section->addText('• ' . $device);
}
$section->addTextBreak(1);

$section->addText('Browser yang Didukung:', array('bold' => true));
$browsers = array(
    'Google Chrome (Direkomendasikan)',
    'Mozilla Firefox',
    'Microsoft Edge',
    'Safari (untuk Mac/iOS)'
);
foreach($browsers as $browser) {
    $section->addText('• ' . $browser);
}
$section->addTextBreak(1);

$section->addTitle('2.2 URL Akses', 3);
$section->addText('Lokal (Development): http://localhost:8000', array('name' => 'Courier New'));
$section->addText('Atau: http://127.0.0.1:8000', array('name' => 'Courier New'));
$section->addText('Production: [URL akan diberikan oleh admin]', array('name' => 'Courier New'));
$section->addTextBreak(1);

$section->addTitle('2.3 Akun Default (untuk Testing)', 3);
$table = $section->addTable('userRoles');
$table->addRow();
$table->addCell(3000)->addText('Role', array('bold' => true));
$table->addCell(4000)->addText('Email', array('bold' => true));
$table->addCell(2000)->addText('Password', array('bold' => true));

$accounts = array(
    array('Administrator', 'admin@test.com', 'password'),
    array('Staff Kesiswaan', 'kesiswaan@test.com', 'password'),
    array('Guru', 'guru@test.com', 'password'),
    array('Siswa', 'siswa@test.com', 'password')
);

foreach($accounts as $account) {
    $table->addRow();
    $table->addCell(3000)->addText($account[0]);
    $table->addCell(4000)->addText($account[1], array('name' => 'Courier New'));
    $table->addCell(2000)->addText($account[2], array('name' => 'Courier New'));
}
$section->addTextBreak(1);
$section->addText('⚠️ Penting: Ganti password default setelah login pertama!', array('bold' => true, 'color' => 'FF0000'));
$section->addPageBreak();

// 3. PANDUAN LOGIN
$section->addTitle('3. PANDUAN LOGIN', 2);
$section->addTextBreak(1);

$section->addTitle('3.1 Langkah-langkah Login', 3);
$login_steps = array(
    'Buka Browser - Pastikan koneksi internet stabil dan gunakan browser yang didukung',
    'Akses URL Sistem - Ketik URL di address bar dan tekan Enter',
    'Masukkan Kredensial - Email dan password yang terdaftar',
    'Klik Tombol Login - Sistem akan memverifikasi dan redirect ke dashboard'
);

$step_num = 1;
foreach($login_steps as $step) {
    $section->addText($step_num . '. ' . $step);
    $step_num++;
}
$section->addTextBreak(1);

$section->addTitle('3.2 Lupa Password', 3);
$section->addText('Jika lupa password:', array('bold' => true));
$forgot_steps = array(
    'Hubungi administrator sekolah',
    'Berikan email yang terdaftar',
    'Admin akan mereset password',
    'Anda akan mendapat password baru',
    'Login dan ganti password segera'
);

$step_num = 1;
foreach($forgot_steps as $step) {
    $section->addText($step_num . '. ' . $step);
    $step_num++;
}
$section->addPageBreak();

// 4. DASHBOARD UTAMA
$section->addTitle('4. DASHBOARD UTAMA', 2);
$section->addTextBreak(1);

$section->addTitle('4.1 Komponen Dashboard', 3);
$section->addText('Dashboard adalah halaman utama setelah login yang menampilkan:');
$section->addTextBreak(1);

$components = array(
    'Header: Logo sekolah, Nama pengguna, Menu logout, Notifikasi',
    'Sidebar: Menu navigasi sesuai role, Collapse/expand menu',
    'Content Area: Statistik utama, Grafik dan chart, Tabel data terbaru',
    'Footer: Informasi sistem, Copyright, Link bantuan'
);

foreach($components as $component) {
    $section->addText('• ' . $component);
}
$section->addTextBreak(1);

$section->addTitle('4.2 Statistik Dashboard', 3);
$dashboard_info = array(
    'Admin/Kesiswaan: Total siswa, pelanggaran, prestasi, sanksi aktif, grafik trend',
    'Guru: Pelanggaran dan prestasi yang diinput, data pending verifikasi',
    'Siswa: Total poin pelanggaran, prestasi, sanksi aktif, riwayat terbaru',
    'Orang Tua: Data lengkap anak, pelanggaran, prestasi, pesan dari sekolah'
);

foreach($dashboard_info as $info) {
    $section->addText('• ' . $info);
}
$section->addPageBreak();

// 5. PANDUAN UNTUK ADMINISTRATOR
$section->addTitle('5. PANDUAN UNTUK ADMINISTRATOR', 2);
$section->addTextBreak(1);

$section->addTitle('5.1 Menu yang Tersedia', 3);
$section->addText('Administrator memiliki akses penuh ke semua fitur:');
$section->addTextBreak(1);

$admin_menus = array(
    'Dashboard - Statistik lengkap sistem dan grafik',
    'Manajemen User - Kelola pengguna, approve/reject, reset password',
    'Data Master - Kelola siswa, guru, kelas, tahun ajaran',
    'Jenis Pelanggaran & Prestasi - Atur kategori dan poin',
    'Verifikasi Data - Verifikasi pelanggaran, prestasi, biodata ortu',
    'Laporan - Generate laporan PDF dan export data'
);

foreach($admin_menus as $menu) {
    $section->addText('• ' . $menu);
}
$section->addTextBreak(1);

$section->addTitle('5.2 Mengelola Data Siswa', 3);
$section->addText('Menambah Siswa Baru:', array('bold' => true));
$section->addTextBreak(1);

$add_student = array(
    'Akses Menu Siswa: Sidebar → Data Master → Siswa',
    'Klik Tombol "Tambah Siswa" (biru di pojok kanan atas)',
    'Isi Form: NIS, Nama, Kelas, Jenis Kelamin, Tahun Ajaran, Alamat, No. Telp, Email',
    'Validasi: Pastikan NIS unik, nama tidak kosong, kelas dipilih, email valid',
    'Simpan: Sistem akan buat akun user otomatis dengan password default'
);

$step_num = 1;
foreach($add_student as $step) {
    $section->addText($step_num . '. ' . $step);
    $step_num++;
}
$section->addTextBreak(1);

// Continue with more essential sections...
$section->addTitle('5.3 Verifikasi Pelanggaran', 3);
$section->addText('Proses Verifikasi:', array('bold' => true));
$verification_steps = array(
    'Akses Menu Pelanggaran dan filter data pending',
    'Review data: siswa, jenis pelanggaran, poin, keterangan, tanggal',
    'Ambil keputusan: Setujui (poin ditambah), Tolak (beri alasan), atau Revisi',
    'Sistem otomatis cek sanksi jika total poin ≥ 100'
);

$step_num = 1;
foreach($verification_steps as $step) {
    $section->addText($step_num . '. ' . $step);
    $step_num++;
}
$section->addPageBreak();

// 6-11. Other user guides (simplified)
$user_guides = array(
    '6. PANDUAN UNTUK STAFF KESISWAAN' => 'Akses hampir sama dengan admin, fokus verifikasi data dan approve biodata ortu',
    '7. PANDUAN UNTUK GURU' => 'Input pelanggaran dan prestasi siswa, monitor status verifikasi',
    '8. PANDUAN UNTUK WALI KELAS' => 'Kelola kelas, komunikasi dengan orang tua, laporan kelas',
    '9. PANDUAN UNTUK GURU BK' => 'Input sesi bimbingan konseling, monitoring siswa bermasalah',
    '10. PANDUAN UNTUK SISWA' => 'Lihat data pribadi, poin pelanggaran, prestasi, sanksi aktif',
    '11. PANDUAN UNTUK ORANG TUA' => 'Daftar akun, lengkapi biodata, monitor data anak, komunikasi sekolah'
);

foreach($user_guides as $title => $description) {
    $section->addTitle($title, 2);
    $section->addText($description);
    $section->addTextBreak(2);
}

// 12. FITUR LAPORAN
$section->addTitle('12. FITUR LAPORAN', 2);
$section->addTextBreak(1);

$section->addText('Jenis Laporan:', array('bold' => true));
$reports = array(
    'Laporan Siswa - Data lengkap siswa per kelas dengan riwayat',
    'Laporan Pelanggaran - Detail pelanggaran per periode dengan statistik',
    'Laporan Prestasi - Detail prestasi per periode dengan trend',
    'Laporan Kelas - Khusus wali kelas untuk statistik kelas'
);

foreach($reports as $report) {
    $section->addText('• ' . $report);
}
$section->addTextBreak(1);

$section->addText('Cara Generate PDF:', array('bold' => true));
$pdf_steps = array(
    'Akses Menu Laporan',
    'Pilih jenis laporan',
    'Set filter: kelas, tanggal mulai, tanggal selesai',
    'Preview data sesuai filter',
    'Klik "Export PDF" dan tunggu download'
);

$step_num = 1;
foreach($pdf_steps as $step) {
    $section->addText($step_num . '. ' . $step);
    $step_num++;
}
$section->addPageBreak();

// 13. TROUBLESHOOTING
$section->addTitle('13. TROUBLESHOOTING', 2);
$section->addTextBreak(1);

$section->addTitle('13.1 Masalah Login', 3);
$login_problems = array(
    'Email/Password Salah: Periksa kembali, pastikan Caps Lock tidak aktif',
    'Akun Belum Diverifikasi: Cek email verifikasi atau hubungi admin',
    'Page Expired: Refresh halaman (F5) atau clear browser cache'
);

foreach($login_problems as $problem) {
    $section->addText('• ' . $problem);
}
$section->addTextBreak(1);

$section->addTitle('13.2 Masalah Input Data', 3);
$input_problems = array(
    'Data Tidak Tersimpan: Cek koneksi internet, pastikan field wajib terisi',
    'Siswa Tidak Ditemukan: Periksa ejaan nama atau gunakan NIS',
    'Upload File Gagal: Cek ukuran (max 2MB) dan format (JPG, PNG, PDF)'
);

foreach($input_problems as $problem) {
    $section->addText('• ' . $problem);
}
$section->addPageBreak();

// 14. FAQ
$section->addTitle('14. FAQ (FREQUENTLY ASKED QUESTIONS)', 2);
$section->addTextBreak(1);

$faqs = array(
    'Q: Bagaimana cara mendapatkan akun?' => 'A: Hubungi administrator sekolah untuk dibuatkan akun sesuai role.',
    'Q: Bisakah satu orang memiliki multiple role?' => 'A: Ya, dengan persetujuan admin (misal guru yang juga wali kelas).',
    'Q: Apakah data aman?' => 'A: Ya, sistem menggunakan enkripsi dan backup otomatis.',
    'Q: Bisakah diakses dari HP?' => 'A: Ya, sistem responsive untuk smartphone/tablet.',
    'Q: Kapan sanksi otomatis dibuat?' => 'A: Ketika total poin pelanggaran siswa mencapai 100 atau lebih.',
    'Q: Browser apa yang direkomendasikan?' => 'A: Google Chrome versi terbaru untuk performa optimal.'
);

foreach($faqs as $question => $answer) {
    $section->addText($question, array('bold' => true));
    $section->addText($answer);
    $section->addTextBreak(1);
}

// KONTAK & BANTUAN
$section->addTitle('KONTAK & BANTUAN', 2);
$section->addTextBreak(1);

$section->addText('Kontak Administrator', array('bold' => true, 'size' => 12));
$section->addText('Email: admin@sekolah.sch.id');
$section->addText('Telepon: (021) 1234-5678');
$section->addText('Jam Kerja: Senin-Jumat, 07:00-15:00');
$section->addTextBreak(1);

$section->addText('Bantuan Teknis', array('bold' => true, 'size' => 12));
$section->addText('Email: support@sekolah.sch.id');
$section->addText('WhatsApp: 0812-3456-7890');
$section->addTextBreak(2);

// Footer
$section->addText('© 2025 SIKAP - Sistem Informasi Kesiswaan dan Prestasi', array('bold' => true, 'color' => '666666'));
$section->addText('Dikembangkan untuk Kemajuan Pendidikan Indonesia', array('italic' => true, 'color' => '666666'));

// Save document
$filename = 'PANDUAN_PENGGUNA_LENGKAP_SIKAP_FIXED.docx';
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');

try {
    $objWriter->save($filename);
    echo "✅ Dokumen berhasil dibuat: " . $filename . "\n";
    echo "📁 Lokasi: " . realpath($filename) . "\n";
    echo "📄 Format: Microsoft Word (.docx)\n";
    echo "📊 Ukuran: " . round(filesize($filename) / 1024, 2) . " KB\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>