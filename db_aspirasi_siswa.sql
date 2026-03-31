-- Database: db_aspirasi_siswa
CREATE DATABASE IF NOT EXISTS db_aspirasi_siswa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_aspirasi_siswa;

-- Tabel Admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Siswa
CREATE TABLE IF NOT EXISTS siswa (
    nis INT(10) PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    kelas VARCHAR(10) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Kategori
CREATE TABLE IF NOT EXISTS kategori (
    id_kategori INT(5) AUTO_INCREMENT PRIMARY KEY,
    ket_kategori VARCHAR(30) NOT NULL
);

-- Tabel Aspirasi
CREATE TABLE IF NOT EXISTS aspirasi (
    id_aspirasi INT(5) AUTO_INCREMENT PRIMARY KEY,
    status ENUM('Menunggu','Proses','Selesai') DEFAULT 'Menunggu',
    id_kategori INT(5) NOT NULL,
    feedback TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);

-- Tabel Input Aspirasi (Pelaporan)
CREATE TABLE IF NOT EXISTS input_aspirasi (
    id_pelaporan INT(5) AUTO_INCREMENT PRIMARY KEY,
    nis INT(10) NOT NULL,
    id_kategori INT(5) NOT NULL,
    id_aspirasi INT(5) NULL,
    lokasi VARCHAR(50) NOT NULL,
    ket VARCHAR(200) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (nis) REFERENCES siswa(nis),
    FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori),
    FOREIGN KEY (id_aspirasi) REFERENCES aspirasi(id_aspirasi)
);

-- Data default Admin
INSERT INTO admin (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Data default Siswa
INSERT INTO siswa (nis, nama, kelas, password) VALUES 
(12345, 'Budi Santoso', 'XII RPL 1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(12346, 'Siti Rahayu', 'XII RPL 2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'),
(12347, 'Ahmad Fauzi', 'XI RPL 1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Data Kategori
INSERT INTO kategori (ket_kategori) VALUES 
('Kebersihan'),
('Fasilitas Belajar'),
('Toilet & Sanitasi'),
('Keamanan'),
('Kantin'),
('Perpustakaan'),
('Laboratorium'),
('Olahraga & Lapangan');

-- Data Aspirasi Contoh
INSERT INTO aspirasi (status, id_kategori, feedback) VALUES 
('Selesai', 1, 'Terima kasih atas laporannya. Petugas kebersihan sudah diperintahkan untuk membersihkan area tersebut.'),
('Proses', 2, 'Laporan sedang ditindaklanjuti oleh pihak sekolah.'),
('Menunggu', 3, NULL);

-- Data Input Aspirasi Contoh
INSERT INTO input_aspirasi (nis, id_kategori, id_aspirasi, lokasi, ket, tanggal) VALUES 
(12345, 1, 1, 'Kelas XII RPL 1', 'Sampah di sudut kelas menumpuk sudah 3 hari tidak dibersihkan', '2026-02-20'),
(12346, 2, 2, 'Lab Komputer', 'Beberapa komputer di lab rusak dan tidak bisa digunakan', '2026-02-25'),
(12347, 3, 3, 'Toilet Putra Lantai 2', 'Toilet tidak ada air dan bau tidak sedap', '2026-03-01');
