# E-Lab

Aplikasi Laravel untuk Data Laboratorium dan Peminjaman. Tahap pertama mencakup login, logout, dan halaman awal untuk dua peran, mahasiswa dan petugas.

## Menjalankan secara lokal

Gunakan PHP 8.2 atau versi kompatibel dengan Laravel 12, Composer, dan ekstensi PDO MySQL, dan MySQL Community Server. Buat database elab dan akun MySQL untuk aplikasi sebelum menjalankan migrasi.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
notepad .env
php artisan migrate --seed
php artisan serve
```

Untuk instalasi baru, isi DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, dan DB_PASSWORD pada .env sesuai server MySQL Anda sebelum migrasi. Jika proyek sudah disiapkan dan MySQL aktif, jalankan `php artisan serve`. Buka [halaman login](http://127.0.0.1:8000/login).

| Peran | Username demo | Kata sandi |
| --- | --- | --- |
| Mahasiswa | Hamdika | 123 |
| Admin | admin | 123 |

Seeder akun demo hanya berjalan di lingkungan `local`. Peran dibaca dari basis data. Dashboard petugas dan pengelolaan alat memerlukan peran admin.

Jalankan pemeriksaan dengan `php artisan test`.
