-- =========================================
-- DATABASE PERPUSTAKAAN KAMPUS
-- =========================================
CREATE DATABASE IF NOT EXISTS db_perpustakaan;
USE db_perpustakaan;
-- =========================================

-- ==========================
-- TABEL users
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','petugas','mahasiswa') NOT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (nama, username, password, role) VALUES
('Admin', 'admin', MD5('123'), 'admin'),
('Petugas', 'petugas', MD5('123'), 'petugas'),
('Blikseqri', 'bliksemqri', MD5('123'), 'mahasiswa');

-- ==========================
-- TABEL mahasiswa
CREATE TABLE mahasiswa (
    id_mahasiswa INT AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100),
    kontak VARCHAR(50),
    id_user INT UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO mahasiswa (nim, nama, prodi, kontak, id_user)
VALUES (
    '175410097',
    'Blikseqri',
    'Teknik Informatika',
    '081234567890',
    (SELECT id_user FROM users WHERE username='bliksemqri')
);

-- ==========================
-- TABEL petugas
CREATE TABLE petugas (
    id_petugas INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    kontak VARCHAR(20),
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- TABEL admin
CREATE TABLE admin (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    nama VARCHAR(100) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- TABEL buku
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    kode_buku VARCHAR(30) UNIQUE NOT NULL,
    judul VARCHAR(200) NOT NULL,
    pengarang VARCHAR(100),
    penerbit VARCHAR(100),
    tahun INT,
    stok INT NOT NULL DEFAULT 0 CHECK (stok >= 0)
) ENGINE=InnoDB;

INSERT INTO buku (kode_buku, judul, pengarang, penerbit, tahun, stok) VALUES
('9789', 'Pemrograman Web Dasar', 'Andi Susanto', 'Erlangga', 2020, 10),
('9770', 'Basis Data Lanjut', 'Budi Santoso', 'Informatika', 2019, 5),
('9760', 'Jaringan Komputer', 'Siti Aminah', 'Graha Ilmu', 2021, 7);

-- ==========================
-- TABEL peminjaman
CREATE TABLE peminjaman (
    id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT NOT NULL,
    id_buku INT NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    tanggal_kembali DATE DEFAULT NULL,
    status ENUM('dipinjam','dikembalikan','terlambat') DEFAULT 'dipinjam',
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa),
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku)
) ENGINE=InnoDB;

INSERT INTO peminjaman
(id_mahasiswa, id_buku, tanggal_pinjam, tanggal_jatuh_tempo, status)
VALUES
(1, 1, '2024-06-01', '2024-06-15', 'dipinjam'),
(1, 2, '2024-06-05', '2024-06-19', 'dikembalikan');

-- ==========================
-- TABEL perpanjangan
CREATE TABLE perpanjangan (
    id_perpanjangan INT AUTO_INCREMENT PRIMARY KEY,
    id_peminjaman INT NOT NULL,
    tanggal_request DATE NOT NULL,
    jenis_pengajuan ENUM('otomatis','manual') NOT NULL DEFAULT 'otomatis',
    judul_manual VARCHAR(255),
    pengarang_manual VARCHAR(255),
    status ENUM('pending','accept','reject') DEFAULT 'pending',
    keterangan TEXT,
    FOREIGN KEY (id_peminjaman) REFERENCES peminjaman(id_peminjaman)
) ENGINE=InnoDB;



INSERT INTO perpanjangan
(id_peminjaman, tanggal_request, status, keterangan)
VALUES
(1, '2024-06-10', 'pending', 'Meminta perpanjangan karena tugas kuliah');

-- ==========================
-- TABEL log aktivitas
CREATE TABLE log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    aktivitas TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

-- ==========================
-- TABEL notifikasi
CREATE TABLE notifikasi (
    id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    pesan TEXT NOT NULL,
    status ENUM('belum_dibaca','dibaca') DEFAULT 'belum_dibaca',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
) ENGINE=InnoDB;

-- ==========================
-- TABEL log perpanjangan
CREATE TABLE log_perpanjangan (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_perpanjangan INT NOT NULL,
    id_buku INT NOT NULL,
    id_mahasiswa INT NOT NULL,
    tanggal_perpanjangan DATE NOT NULL,
    aksi ENUM('ajukan','setujui','tolak') NOT NULL,
    status ENUM('pending','accept','reject') DEFAULT 'pending',
    keterangan TEXT,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_perpanjangan) REFERENCES perpanjangan(id_perpanjangan)
) ENGINE=InnoDB;
