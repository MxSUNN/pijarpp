# Instalasi PIJAR–SUAR Laravel 12

## Persyaratan
- PHP 8.4
- Composer 2
- Node.js 20+
- MySQL 8+

## Langkah
1. Ekstrak paket ke folder project Laravel kosong Anda.
2. Jalankan `composer install` dan `npm install`.
3. Salin `.env.example` menjadi `.env`, lalu isi `DB_CONNECTION=mysql`, host, database, username, dan password.
4. Jalankan `php artisan key:generate`.
5. Jalankan `php artisan migrate --seed`.
6. Jalankan `npm run build`.
7. Jalankan `php artisan serve` untuk pengembangan lokal.

## Akun demo
Semua akun memakai kata sandi `Demo123!`:
- superadmin@pijar.demo
- admin@pijar.demo
- kerma@pijar.demo
- kaprodi@pijar.demo
- dosen@pijar.demo

Ganti kata sandi demo sebelum production. Public schedule hanya menampilkan mata kuliah, prodi, tanggal, waktu, dan lokasi.
