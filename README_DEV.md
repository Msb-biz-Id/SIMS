## Menjalankan Proyek dengan Laragon (Windows)

Prasyarat: Laragon (Apache + MySQL) terpasang.

Langkah:
- Pindahkan folder proyek ini ke `C:\\laragon\\www\\platform-mst` (nama bebas)
- Pastikan Laragon berjalan, klik Menu > www > akses `http://platform-mst.test` (Laragon auto virtual-host). Laragon otomatis menggunakan folder `public/` sebagai DocumentRoot.

Konfigurasi .env:
- Salin `.env.example` menjadi `.env` lalu sesuaikan jika perlu. Default untuk Laragon:
  - `DB_HOST=127.0.0.1`
  - `DB_NAME=app`
  - `DB_USER=root`
  - `DB_PASS=` (kosong)
  - `APP_BASE_URL=` (biarkan kosong agar otomatis terdeteksi)

Rute dasar:
- `/` dashboard (SistemDataMaster)
- `/sims/surat-masuk`, `/sims/surat-keluar`, `/sims/laporan-agenda`
- `/keuangan`, `/keuangan/invoice-gaji`, `/keuangan/laporan`
- `/program-kerja`, `/program-kerja/anggaran`

Struktur penting:
- `public/` front controller (`index.php`) & aset publik (`assets/`)
- `app/` Core, Config, dan modul MVC
- `app/Views/layouts/main.php` layout utama berdasarkan template `assets`

Catatan:
- `.htaccess` di `public/` sudah menyiapkan URL rewrite.
- Jika domain `.test` belum aktif, di Laragon gunakan Menu > Preferensi > Periksa auto virtual hosts.