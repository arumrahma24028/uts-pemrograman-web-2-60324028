# UTS Pemrograman Web 2

## Identitas
- Nama: Arum Rahma Putri Sabrina
- NIM: 60324028


## Deskripsi Aplikasi
Aplikasi ini adalah sistem manajemen perpustakaan sederhana berbasis web yang dibuat menggunakan PHP dan MySQL.

Fitur utama:
- CRUD Kategori Buku
- Validasi form
- Notifikasi sukses dan error
- Tampilan menggunakan Bootstrap 5


## Cara Instalasi

1. Clone repository:
```bash

git clone https://github.com/username/uts-pemrograman-web-2-60324028.git

2. Pindahkan ke Folder Web Server

Jika menggunakan Laragon:

C:\laragon\www\

Jika menggunakan XAMPP:

C:\xampp\htdocs\

3. Buat Database
Buka phpMyAdmin
Klik New
Buat database dengan nama:
uts_perpustakaan_60324028

4. Import Database
Pilih database yang sudah dibuat
Klik tab Import
Upload file:
database/database_backup.sql
Klik Go

5. Konfigurasi Database

Buka file:

config/database.php

Sesuaikan jika perlu:

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'uts_perpustakaan_60324028');

6. Jalankan Aplikasi

Buka browser dan akses:

http://localhost/uts-pemrograman-web-2-60324028/

