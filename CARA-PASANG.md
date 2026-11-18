# Memindahkan E-Lab ke komputer lain

ZIP ini berisi kode Laravel, dependensi PHP di vendor, foto alat, serta
ekspor struktur dan data MySQL di database/elab.sql. Sesi login dan cache
tidak ikut dipindahkan.

1. Ekstrak ZIP, lalu buka folder E-Lab di VS Code.
2. Pastikan PHP 8.2 atau yang kompatibel dengan Laravel 12 tersedia, ekstensi
   pdo_mysql aktif, dan MySQL Community Server berjalan.
3. Buat schema kosong bernama elab di MySQL. Di MySQL Workbench, pilih schema
   tersebut sebagai schema aktif, buka database/elab.sql, lalu jalankan seluruh
   skrip. Gunakan schema kosong karena dump membuat ulang tabel yang ada.
4. Di terminal PowerShell VS Code, jalankan Copy-Item .env.example .env.
   Buka .env dan isi DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, dan
   DB_PASSWORD sesuai akun MySQL di komputer tujuan. Akun harus punya akses
   ke schema elab.
5. Jalankan php artisan key:generate, lalu php artisan serve.
   Buka alamat yang ditampilkan terminal dan tambahkan /login.

Gunakan akun E-Lab yang sudah tersimpan. Untuk akun awal, pilih Petugas Lab
dengan username admin atau Mahasiswa dengan username Hamdika. Password awal
keduanya 123, jika belum diubah. Jika seluruh dump sudah diimpor, langsung
jalankan aplikasi dengan data hasil impor.

Paket berisi data akun dan inventaris. Simpan dan bagikan ZIP hanya kepada
pihak yang boleh mengakses data E-Lab.
