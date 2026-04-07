-- membuat database
CREATE DATABASE IF NOT EXISTS db_jualbaju;
USE db_jualbaju;

-- tabel users
CREATE TABLE IF NOT EXISTS users (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role     ENUM('admin', 'user') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- tabel products
CREATE TABLE IF NOT EXISTS products (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nama_produk  VARCHAR(100) NOT NULL,
    harga        INT NOT NULL,
    stok         INT NOT NULL DEFAULT 0,
    deskripsi    TEXT,
    gambar       VARCHAR(255),
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP
);