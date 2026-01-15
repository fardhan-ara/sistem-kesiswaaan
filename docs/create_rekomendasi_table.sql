-- SQL untuk membuat tabel rekomendasi_executives
-- Jalankan query ini di phpMyAdmin atau MySQL client

CREATE TABLE IF NOT EXISTS `rekomendasi_executives` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `rekomendasi` text NOT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contoh data rekomendasi
INSERT INTO `rekomendasi_executives` (`rekomendasi`, `periode`, `is_active`, `created_at`, `updated_at`) VALUES
('Tingkatkan program pembinaan karakter siswa melalui kegiatan ekstrakurikuler', 'Semester 1 2024/2025', 1, NOW(), NOW()),
('Adakan workshop untuk guru tentang metode pembelajaran modern', 'Semester 1 2024/2025', 1, NOW(), NOW());
