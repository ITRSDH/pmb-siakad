
# PMB-SIAKAD — Panduan Instalasi Laravel (Windows / Laragon)

Dokumentasi singkat untuk mengatur dan menjalankan project Laravel ini pada lingkungan Windows, khususnya dengan Laragon. Panduan ini dibuat untuk pengembang pemula hingga menengah.

## Prasyarat

- Windows (10/11)
- Laragon (direkomendasikan) atau PHP >= 8.1, Composer, MySQL/MariaDB
- Git
- Node.js & npm (untuk kompilasi asset)

Jika menggunakan Laragon, banyak dependensi akan tersedia secara otomatis.

## Langkah cepat — Buat project baru (opsional)

Jika Anda ingin membuat project Laravel baru (bukan dari repo ini), gunakan Composer:

```powershell
# Dengan Composer
composer create-project laravel/laravel nama-project

# Atau jika Anda memiliki Laravel installer
laravel new nama-project
```

Untuk repo ini: cukup clone atau letakkan folder di Laragon `www` lalu lanjut ke konfigurasi.

## 1) Mengatur environment (.env)

Salin file `.env.example` menjadi `.env` (jika belum ada):

```powershell
cd C:\laragon\www\persiapan-presentasi\pmb-siakad - Copy
if (-Not (Test-Path .env)) { Copy-Item .env.example .env }
```

Edit `.env` sesuai konfigurasi lokal Anda (database, mail, dll). Contoh bagian database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Jika Anda menggunakan Laragon, Anda dapat membuat database lewat Menu > Database > Adminer / phpMyAdmin, atau terminal MySQL.

## 2) Install dependency PHP (Composer)

Jalankan di PowerShell pada root project:

```powershell
composer install
```

Jika composer menanyakan credential atau GIT, pastikan koneksi internet dan akses ke packagist/github bekerja.

## 3) Generate application key

```powershell
php artisan key:generate
```

Perintah ini akan mengisi `APP_KEY` di `.env`.

## 4) Migrasi dan seeding database

Jalankan migrasi (dan seeding jika ada):

```powershell
php artisan migrate
# Jika ingin menjalankan seeder
php artisan db:seed
```

Catatan: Jika Anda menggunakan MySQL baru, pastikan `DB_DATABASE` sudah dibuat.

## 5) Install & build frontend (opsional)

Instal Node deps dan build assets:

```powershell
npm install
# Dev build (watch)
npm run dev
# Or production build
npm run build
```

Jika menggunakan Laragon + hot reload, Anda bisa menjalankan `npm run dev` dan mengakses app via Laragon domain.

## 6) Jalankan server lokal

Anda punya beberapa opsi:

- Gunakan built-in server Laravel (development):

```powershell
php artisan serve --host=127.0.0.1 --port=8000
# lalu akses http://127.0.0.1:8000
```

- Atau gunakan Laragon: letakkan repo di folder `www`, lalu aktifkan Apache/Nginx melalui Laragon dan buka domain yang telah dibuat.

## 7) Konfigurasi tambahan untuk Laragon (opsional)

Jika menggunakan Laragon, Anda dapat membuat virtual host otomatis: klik kanan Laragon → Quick app → New → Isikan folder project. Atau gunakan menu `www` dan `restart` untuk mendeteksi.

## 8) Menjalankan test (jika ada)

Jika project memiliki test PHPUnit, jalankan:

```powershell
./vendor/bin/phpunit
```

Atau via artisan (Laravel 9+):

```powershell
php artisan test
```

## Troubleshooting umum

- 500 / error koneksi DB: periksa `DB_*` di `.env` dan pastikan database sudah dibuat.
- Permission file: di Windows biasanya bukan masalah, tapi pastikan storage dan bootstrap/cache dapat ditulis oleh user.
- Dependensi tidak terinstall: jalankan `composer install` dan `npm install` lagi, periksa versi PHP.

## Tips

- Gunakan Git untuk versi kontrol. Contoh setting remote dan push:

```powershell
git remote -v
git push origin main
```

- Untuk mempermudah workflow di mesin pengembangan, gunakan SSH key untuk akses GitHub.

## Kontak / Sumber

- Dokumentasi Laravel: https://laravel.com/docs
- Laracasts (tutorial video): https://laracasts.com

---

Lisensi: sesuaikan dengan lisensi project (file LICENSE atau keputusan tim).

