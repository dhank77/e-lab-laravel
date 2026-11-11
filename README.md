# E-Lab — Sistem Peminjaman Barang Laboratorium

Aplikasi web Laravel 13 + Blade + MySQL untuk mengelola data laboratorium dan peminjaman barang.

## Fitur
- **Login & Register** (mahasiswa mendaftar dengan NIM; akun baru otomatis ber-role `user`)
- **Data Lab**: daftar & detail lab beserta inventaris barangnya
- **Peminjaman**: mahasiswa mengajukan pinjam (jumlah, tanggal pinjam/kembali, keperluan), bisa membatalkan selama masih *Menunggu*
- **Admin**: CRUD lab & barang, setujui / tolak pengajuan, tandai barang dikembalikan
- Stok tersedia dihitung otomatis: `stok − jumlah yang sedang dipinjam`; barang *Rusak Berat* tidak bisa dipinjam
- Penanda **Terlambat** untuk peminjaman yang melewati tanggal kembali

## Cara Menjalankan
1. Buat database MySQL:
   ```sql
   CREATE DATABASE e_lab;
   ```
2. Sesuaikan `.env` (`DB_USERNAME`, `DB_PASSWORD`). Jika belum ada `.env`: `cp .env.example .env && php artisan key:generate`
3. Install & migrasi:
   ```bash
   composer install
   php artisan migrate --seed
   php artisan serve
   ```
4. Buka http://localhost:8000

## Akun Demo (dari seeder)
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@elab.test | password |
| Mahasiswa | mahasiswa@elab.test | password |

## Struktur Database
- `users` — name, nim, email, phone, role (`admin`/`user`), password
- `labs` — code, name, location, head, description
- `items` — lab_id, code, name, category, stock, condition, description
- `loans` — user_id, item_id, quantity, loan_date, due_date, returned_at, purpose, status (`pending`/`approved`/`rejected`/`returned`), admin_note

## Testing
```bash
php artisan test
```
