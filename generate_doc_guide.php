<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;

// Create new PhpWord object
$phpWord = new PhpWord();

// Set document properties
$properties = $phpWord->getDocInfo();
$properties->setCreator('SIKAP System');
$properties->setCompany('Sekolah');
$properties->setTitle('Panduan Pengguna Lengkap - Sistem Kesiswaan (SIKAP)');
$properties->setDescription('Panduan lengkap penggunaan sistem informasi kesiswaan dan prestasi');
$properties->setCategory('Documentation');
$properties->setLastModifiedBy('Admin');
$properties->setCreated(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
$properties->setModified(mktime(0, 0, 0, date('m'), date('d'), date('Y')));
$properties->setSubject('User Guide');
$properties->setKeywords('SIKAP, Sistem Kesiswaan, User Guide, Panduan');

// Define styles
$phpWord->addTitleStyle(1, array('size' => 20, 'bold' => true, 'color' => '2E74B5'));
$phpWord->addTitleStyle(2, array('size' => 16, 'bold' => true, 'color' => '1F4E79'));
$phpWord->addTitleStyle(3, array('size' => 14, 'bold' => true, 'color' => '365F91'));
$phpWord->addTitleStyle(4, array('size' => 12, 'bold' => true));

// Add section
$section = $phpWord->addSection();

// Title
$section->addTitle('PANDUAN PENGGUNA LENGKAP', 1);
$section->addTitle('SISTEM KESISWAAN (SIKAP)', 1);
$section->addTextBreak(1);

$textrun = $section->addTextRun();
$textrun->addText('Sistem Informasi Kesiswaan dan Prestasi', array('bold' => true, 'size' => 14));
$section->addTextBreak(1);
$textrun = $section->addTextRun();
$textrun->addText('Panduan Lengkap untuk Semua Pengguna', array('italic' => true, 'size' => 12));
$section->addTextBreak(2);

// Table of Contents
$section->addTitle('DAFTAR ISI', 2);
$section->addTextBreak(1);

$toc_items = [
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
];

foreach($toc_items as $item) {
    $section->addText($item, array('size' => 11));
}
$section->addPageBreak();

// 1. PENGENALAN SISTEM
$section->addTitle('1. PENGENALAN SISTEM', 2);
$section->addTextBreak(1);

$section->addTitle('1.1 Apa itu SIKAP?', 3);
$section->addText('SIKAP (Sistem Informasi Kesiswaan dan Prestasi) adalah aplikasi berbasis web yang dirancang khusus untuk mengelola seluruh aspek kesiswaan di sekolah. Sistem ini membantu sekolah dalam:', array('size' => 11));
$section->addTextBreak(1);

$benefits = [
    'Mengelola data siswa secara terpusat dan terorganisir',
    'Mencatat pelanggaran dengan sistem poin otomatis',
    'Mencatat prestasi siswa dengan berbagai tingkatan',
    'Mengelola sanksi yang diberikan secara otomatis',
    'Melakukan bimbingan konseling dengan dokumentasi lengkap',
    'Berkomunikasi dengan orang tua secara real-time',
    'Membuat laporan dalam format PDF'
];

foreach($benefits as $benefit) {
    $textrun = $section->addTextRun();
    $textrun->addText('• ', array('bold' => true));
    $textrun->addText($benefit, array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('1.2 Keunggulan Sistem', 3);
$advantages = [
    'Otomatisasi Sanksi: Sanksi dibuat otomatis ketika poin pelanggaran mencapai 100',
    'Verifikasi Berlapis: Setiap data melalui proses verifikasi untuk akurasi',
    'Komunikasi Real-time: Orang tua dapat memantau anak secara langsung',
    'Laporan Lengkap: Export laporan dalam format PDF dengan filter',
    'Multi-Role: Mendukung 7 jenis pengguna dengan hak akses berbeda',
    'Responsive: Dapat diakses dari komputer, tablet, dan smartphone'
];

foreach($advantages as $advantage) {
    $textrun = $section->addTextRun();
    $textrun->addText('✓ ', array('bold' => true, 'color' => '00AA00'));
    $textrun->addText($advantage, array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('1.3 Jenis Pengguna', 3);

// Create table for user roles
$table = $section->addTable(array('borderSize' => 6, 'borderColor' => '999999'));
$table->addRow();
$table->addCell(800)->addText('No', array('bold' => true));
$table->addCell(2000)->addText('Role', array('bold' => true));
$table->addCell(3000)->addText('Deskripsi', array('bold' => true));
$table->addCell(3000)->addText('Hak Akses', array('bold' => true));

$roles = [
    ['1', 'Administrator', 'Pengelola utama sistem', 'Akses penuh semua fitur'],
    ['2', 'Staff Kesiswaan', 'Pengelola data kesiswaan', 'Hampir sama dengan admin'],
    ['3', 'Guru', 'Pengajar di sekolah', 'Input pelanggaran & prestasi'],
    ['4', 'Wali Kelas', 'Guru yang mengelola kelas', 'Kelola kelas + komunikasi ortu'],
    ['5', 'Guru BK', 'Guru Bimbingan Konseling', 'Bimbingan konseling siswa'],
    ['6', 'Siswa', 'Peserta didik', 'Lihat data pribadi'],
    ['7', 'Orang Tua', 'Wali siswa', 'Monitor data anak']
];

foreach($roles as $role) {
    $table->addRow();
    $table->addCell(800)->addText($role[0]);
    $table->addCell(2000)->addText($role[1], array('bold' => true));
    $table->addCell(3000)->addText($role[2]);
    $table->addCell(3000)->addText($role[3]);
}
$section->addPageBreak();

// 2. CARA MENGAKSES SISTEM
$section->addTitle('2. CARA MENGAKSES SISTEM', 2);
$section->addTextBreak(1);

$section->addTitle('2.1 Persyaratan Sistem', 3);
$section->addText('Perangkat yang Didukung:', array('bold' => true, 'size' => 11));
$devices = [
    'Komputer/Laptop (Windows, Mac, Linux)',
    'Tablet (Android, iOS)',
    'Smartphone (Android, iOS)'
];
foreach($devices as $device) {
    $textrun = $section->addTextRun();
    $textrun->addText('• ', array('bold' => true));
    $textrun->addText($device, array('size' => 11));
}
$section->addTextBreak(1);

$section->addText('Browser yang Didukung:', array('bold' => true, 'size' => 11));
$browsers = [
    'Google Chrome (Direkomendasikan)',
    'Mozilla Firefox',
    'Microsoft Edge',
    'Safari (untuk Mac/iOS)'
];
foreach($browsers as $browser) {
    $textrun = $section->addTextRun();
    $textrun->addText('• ', array('bold' => true));
    $textrun->addText($browser, array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('2.2 URL Akses', 3);
$section->addText('Lokal (Development): http://localhost:8000', array('size' => 11, 'name' => 'Courier New'));
$section->addText('Atau: http://127.0.0.1:8000', array('size' => 11, 'name' => 'Courier New'));
$section->addTextBreak(1);
$section->addText('Production: [URL akan diberikan oleh admin]', array('size' => 11, 'name' => 'Courier New'));
$section->addTextBreak(1);

$section->addTitle('2.3 Akun Default (untuk Testing)', 3);
$table = $section->addTable(array('borderSize' => 6, 'borderColor' => '999999'));
$table->addRow();
$table->addCell(3000)->addText('Role', array('bold' => true));
$table->addCell(4000)->addText('Email', array('bold' => true));
$table->addCell(2000)->addText('Password', array('bold' => true));

$accounts = [
    ['Administrator', 'admin@test.com', 'password'],
    ['Staff Kesiswaan', 'kesiswaan@test.com', 'password'],
    ['Guru', 'guru@test.com', 'password'],
    ['Siswa', 'siswa@test.com', 'password']
];

foreach($accounts as $account) {
    $table->addRow();
    $table->addCell(3000)->addText($account[0]);
    $table->addCell(4000)->addText($account[1], array('name' => 'Courier New'));
    $table->addCell(2000)->addText($account[2], array('name' => 'Courier New'));
}
$section->addTextBreak(1);

$textrun = $section->addTextRun();
$textrun->addText('⚠️ Penting: ', array('bold' => true, 'color' => 'FF0000'));
$textrun->addText('Ganti password default setelah login pertama!', array('size' => 11));
$section->addPageBreak();

// 3. PANDUAN LOGIN
$section->addTitle('3. PANDUAN LOGIN', 2);
$section->addTextBreak(1);

$section->addTitle('3.1 Langkah-langkah Login', 3);
$login_steps = [
    'Buka Browser: Pastikan koneksi internet stabil, Gunakan browser yang didukung',
    'Akses URL Sistem: Ketik URL di address bar, Tekan Enter',
    'Masukkan Kredensial: Email (Masukkan email yang terdaftar), Password (Masukkan password yang benar), Centang "Remember Me" jika diperlukan',
    'Klik Tombol Login: Sistem akan memverifikasi data, Jika berhasil, akan diarahkan ke dashboard'
];

$step_num = 1;
foreach($login_steps as $step) {
    $textrun = $section->addTextRun();
    $textrun->addText($step_num . '. ', array('bold' => true));
    $textrun->addText($step, array('size' => 11));
    $step_num++;
}
$section->addTextBreak(1);

$section->addTitle('3.2 Lupa Password', 3);
$section->addText('Jika lupa password:', array('bold' => true, 'size' => 11));
$forgot_steps = [
    'Hubungi administrator sekolah',
    'Berikan email yang terdaftar',
    'Admin akan mereset password',
    'Anda akan mendapat password baru',
    'Login dan ganti password segera'
];

$step_num = 1;
foreach($forgot_steps as $step) {
    $textrun = $section->addTextRun();
    $textrun->addText($step_num . '. ', array('bold' => true));
    $textrun->addText($step, array('size' => 11));
    $step_num++;
}
$section->addPageBreak();

// 4. DASHBOARD UTAMA
$section->addTitle('4. DASHBOARD UTAMA', 2);
$section->addTextBreak(1);

$section->addTitle('4.1 Komponen Dashboard', 3);
$section->addText('Dashboard adalah halaman utama setelah login yang menampilkan:', array('size' => 11));
$section->addTextBreak(1);

$components = [
    'Header: Logo sekolah, Nama pengguna, Menu logout, Notifikasi',
    'Sidebar: Menu navigasi sesuai role, Collapse/expand menu, Indikator menu aktif',
    'Content Area: Statistik utama, Grafik dan chart, Tabel data terbaru, Quick actions',
    'Footer: Informasi sistem, Copyright, Link bantuan'
];

foreach($components as $component) {
    $parts = explode(': ', $component, 2);
    $textrun = $section->addTextRun();
    $textrun->addText($parts[0] . ': ', array('bold' => true, 'size' => 11));
    $textrun->addText($parts[1], array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('4.2 Statistik Dashboard', 3);

$dashboard_types = [
    'Untuk Admin/Kesiswaan' => [
        'Total siswa aktif',
        'Total pelanggaran bulan ini',
        'Total prestasi bulan ini',
        'Sanksi aktif',
        'Grafik trend pelanggaran',
        'Grafik trend prestasi'
    ],
    'Untuk Guru' => [
        'Pelanggaran yang diinput',
        'Prestasi yang diinput',
        'Data pending verifikasi',
        'Aktivitas bulan ini'
    ],
    'Untuk Siswa' => [
        'Total poin pelanggaran',
        'Total prestasi',
        'Sanksi aktif (jika ada)',
        'Riwayat terbaru'
    ],
    'Untuk Orang Tua' => [
        'Data lengkap anak',
        'Pelanggaran anak',
        'Prestasi anak',
        'Pesan dari sekolah'
    ]
];

foreach($dashboard_types as $type => $items) {
    $section->addText($type . ':', array('bold' => true, 'size' => 11));
    foreach($items as $item) {
        $textrun = $section->addTextRun();
        $textrun->addText('• ', array('bold' => true));
        $textrun->addText($item, array('size' => 11));
    }
    $section->addTextBreak(1);
}
$section->addPageBreak();

// 5. PANDUAN UNTUK ADMINISTRATOR
$section->addTitle('5. PANDUAN UNTUK ADMINISTRATOR', 2);
$section->addTextBreak(1);

$section->addTitle('5.1 Menu yang Tersedia', 3);
$section->addText('Administrator memiliki akses penuh ke semua fitur:', array('size' => 11));
$section->addTextBreak(1);

$admin_menus = [
    'Dashboard: Statistik lengkap sistem, Grafik pelanggaran & prestasi, Monitor aktivitas pengguna',
    'Manajemen User: Kelola semua pengguna, Approve/reject pendaftaran, Reset password, Atur role dan permission',
    'Data Master: Kelola data siswa, Kelola data guru, Kelola data kelas, Kelola tahun ajaran',
    'Jenis Pelanggaran & Prestasi: Atur kategori pelanggaran, Atur poin pelanggaran, Atur jenis prestasi, Atur poin prestasi',
    'Verifikasi Data: Verifikasi pelanggaran, Verifikasi prestasi, Approve biodata orang tua',
    'Laporan: Generate laporan PDF, Export data, Statistik lengkap'
];

foreach($admin_menus as $menu) {
    $parts = explode(': ', $menu, 2);
    $textrun = $section->addTextRun();
    $textrun->addText($parts[0] . ': ', array('bold' => true, 'size' => 11));
    $textrun->addText($parts[1], array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('5.2 Mengelola Data Siswa', 3);
$section->addText('Menambah Siswa Baru:', array('bold' => true, 'size' => 11));
$section->addTextBreak(1);

$add_student_steps = [
    'Akses Menu Siswa: Sidebar → Data Master → Siswa',
    'Klik Tombol "Tambah Siswa": Tombol berwarna biru di pojok kanan atas',
    'Isi Form Data Siswa: NIS (Nomor Induk Siswa - Wajib Unik), Nama Lengkap (Nama siswa sesuai dokumen), Kelas (Pilih dari dropdown), Jenis Kelamin (Laki-laki/Perempuan), Tahun Ajaran (Pilih tahun ajaran aktif), Alamat (Alamat lengkap siswa), No. Telepon (Nomor yang bisa dihubungi), Email (Email untuk akun siswa - opsional)',
    'Validasi Data: Pastikan NIS belum digunakan, Nama tidak boleh kosong, Kelas harus dipilih, Format email harus benar',
    'Simpan Data: Klik tombol "Simpan", Sistem akan membuat akun user otomatis, Password default: "password"'
];

$step_num = 1;
foreach($add_student_steps as $step) {
    $parts = explode(': ', $step, 2);
    $textrun = $section->addTextRun();
    $textrun->addText($step_num . '. ', array('bold' => true));
    $textrun->addText($parts[0] . ': ', array('bold' => true, 'size' => 11));
    if(isset($parts[1])) {
        $textrun->addText($parts[1], array('size' => 11));
    }
    $step_num++;
}
$section->addPageBreak();

// Continue with more sections...
// 6. PANDUAN UNTUK STAFF KESISWAAN
$section->addTitle('6. PANDUAN UNTUK STAFF KESISWAAN', 2);
$section->addTextBreak(1);

$section->addTitle('6.1 Perbedaan dengan Administrator', 3);
$section->addText('Staff Kesiswaan memiliki akses hampir sama dengan Administrator, kecuali:', array('size' => 11));
$kesiswaan_limits = [
    'Tidak bisa mengelola user admin lain',
    'Tidak bisa mengakses sistem backup',
    'Fokus pada data kesiswaan'
];
foreach($kesiswaan_limits as $limit) {
    $textrun = $section->addTextRun();
    $textrun->addText('• ', array('bold' => true));
    $textrun->addText($limit, array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('6.2 Tugas Utama', 3);
$tasks = [
    'Harian: Verifikasi pelanggaran & prestasi, Approve biodata orang tua, Monitor sanksi aktif, Respon komunikasi darurat',
    'Mingguan: Generate laporan mingguan, Review trend pelanggaran, Koordinasi dengan wali kelas',
    'Bulanan: Laporan bulanan ke kepala sekolah, Evaluasi efektivitas sanksi, Update data master jika perlu'
];

foreach($tasks as $task) {
    $parts = explode(': ', $task, 2);
    $textrun = $section->addTextRun();
    $textrun->addText($parts[0] . ': ', array('bold' => true, 'size' => 11));
    $textrun->addText($parts[1], array('size' => 11));
}
$section->addPageBreak();

// 7. PANDUAN UNTUK GURU
$section->addTitle('7. PANDUAN UNTUK GURU', 2);
$section->addTextBreak(1);

$section->addTitle('7.1 Menu yang Tersedia', 3);
$guru_menus = [
    'Dashboard Guru: Statistik pelanggaran yang diinput, Statistik prestasi yang diinput, Status verifikasi data',
    'Input Pelanggaran: Form input pelanggaran siswa, Riwayat pelanggaran yang diinput',
    'Input Prestasi: Form input prestasi siswa, Riwayat prestasi yang diinput'
];

foreach($guru_menus as $menu) {
    $parts = explode(': ', $menu, 2);
    $textrun = $section->addTextRun();
    $textrun->addText($parts[0] . ': ', array('bold' => true, 'size' => 11));
    $textrun->addText($parts[1], array('size' => 11));
}
$section->addTextBreak(1);

$section->addTitle('7.2 Menginput Pelanggaran', 3);
$section->addText('Langkah-langkah Detail:', array('bold' => true, 'size' => 11));
$section->addTextBreak(1);

$pelanggaran_steps = [
    'Akses Menu Pelanggaran: Sidebar → Pelanggaran → Tambah Pelanggaran',
    'Pilih Siswa: Ketik nama siswa di search box, Atau pilih dari dropdown, Sistem akan menampilkan kelas siswa',
    'Pilih Jenis Pelanggaran: Kedisiplinan (5-15 poin), Ketertiban (10-25 poin), Sopan Santun (15-30 poin), Keamanan (20-50 poin), Berat (50-100 poin)',
    'Isi Detail Pelanggaran: Tanggal Kejadian, Waktu, Tempat, Keterangan, Saksi (jika ada)',
    'Upload Bukti (Opsional): Foto kejadian, Dokumen pendukung, Format: JPG, PNG, PDF, Maksimal 2MB per file',
    'Review dan Simpan: Periksa kembali semua data, Pastikan akurat dan objektif, Klik "Simpan", Status: "Menunggu Verifikasi"'
];

$step_num = 1;
foreach($pelanggaran_steps as $step) {
    $parts = explode(': ', $step, 2);
    $textrun = $section->addTextRun();
    $textrun->addText($step_num . '. ', array('bold' => true));
    $textrun->addText($parts[0] . ': ', array('bold' => true, 'size' => 11));
    if(isset($parts[1])) {
        $textrun->addText($parts[1], array('size' => 11));
    }
    $step_num++;
}
$section->addTextBreak(1);

$section->addText('Tips Input Pelanggaran:', array('bold' => true, 'size' => 11));
$tips = [
    'Tulis keterangan yang jelas dan objektif',
    'Sertakan bukti jika memungkinkan',
    'Input segera setelah kejadian',
    'Hindari bahasa yang emosional'
];
foreach($tips as $tip) {
    $textrun = $section->addTextRun();
    $textrun->addText('✓ ', array('bold' => true, 'color' => '00AA00'));
    $textrun->addText($tip, array('size' => 11));
}
$section->addPageBreak();

// Continue with remaining sections...
// Add FAQ section
$section->addTitle('14. FAQ (FREQUENTLY ASKED QUESTIONS)', 2);
$section->addTextBreak(1);

$section->addTitle('14.1 Pertanyaan Umum', 3);
$faqs = [
    'Q: Bagaimana cara mendapatkan akun?' => 'A: Hubungi administrator sekolah untuk dibuatkan akun sesuai role Anda.',
    'Q: Bisakah satu orang memiliki multiple role?' => 'A: Ya, dengan persetujuan admin. Misalnya guru yang juga wali kelas.',
    'Q: Apakah data aman?' => 'A: Ya, sistem menggunakan enkripsi dan backup otomatis untuk keamanan data.',
    'Q: Bisakah diakses dari HP?' => 'A: Ya, sistem responsive dan bisa diakses dari smartphone/tablet.'
];

foreach($faqs as $question => $answer) {
    $textrun = $section->addTextRun();
    $textrun->addText($question, array('bold' => true, 'size' => 11));
    $section->addText($answer, array('size' => 11));
    $section->addTextBreak(1);
}

$section->addTitle('14.2 Pertanyaan Teknis', 3);
$tech_faqs = [
    'Q: Browser apa yang direkomendasikan?' => 'A: Google Chrome versi terbaru untuk performa optimal.',
    'Q: Bagaimana jika lupa password?' => 'A: Hubungi administrator untuk reset password.',
    'Q: Bisakah data diekspor?' => 'A: Ya, tersedia fitur export PDF untuk laporan.',
    'Q: Apakah ada backup data?' => 'A: Ya, sistem melakukan backup otomatis setiap hari.'
];

foreach($tech_faqs as $question => $answer) {
    $textrun = $section->addTextRun();
    $textrun->addText($question, array('bold' => true, 'size' => 11));
    $section->addText($answer, array('size' => 11));
    $section->addTextBreak(1);
}

// Contact section
$section->addTitle('KONTAK & BANTUAN', 2);
$section->addTextBreak(1);

$section->addText('Kontak Administrator', array('bold' => true, 'size' => 12));
$section->addText('Email: admin@sekolah.sch.id', array('size' => 11));
$section->addText('Telepon: (021) 1234-5678', array('size' => 11));
$section->addText('Jam Kerja: Senin-Jumat, 07:00-15:00', array('size' => 11));
$section->addTextBreak(1);

$section->addText('Bantuan Teknis', array('bold' => true, 'size' => 12));
$section->addText('Email: support@sekolah.sch.id', array('size' => 11));
$section->addText('WhatsApp: 0812-3456-7890', array('size' => 11));
$section->addTextBreak(2);

// Footer
$textrun = $section->addTextRun();
$textrun->addText('© 2025 SIKAP - Sistem Informasi Kesiswaan dan Prestasi', array('bold' => true, 'size' => 10, 'color' => '666666'));
$section->addText('Dikembangkan untuk Kemajuan Pendidikan Indonesia', array('italic' => true, 'size' => 10, 'color' => '666666'));

// Save document
$filename = 'PANDUAN_PENGGUNA_LENGKAP_SIKAP.docx';
$objWriter = IOFactory::createWriter($phpWord, 'Word2007');

try {
    $objWriter->save($filename);
    echo "✅ Dokumen berhasil dibuat: " . $filename . "\n";
    echo "📁 Lokasi file: " . realpath($filename) . "\n";
    echo "📄 Format: Microsoft Word (.docx)\n";
    echo "📊 Ukuran: " . round(filesize($filename) / 1024, 2) . " KB\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>