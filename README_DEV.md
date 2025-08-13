# Cara Menjalankan Proyek (Dev)

Prasyarat: Docker + Docker Compose

- Build & jalankan:

```bash
docker compose up --build
```

- Akses aplikasi: `http://localhost:8080`
- Akses MySQL: host `127.0.0.1`, port `33060`, user `app`, pass `app`, db `app`

Struktur direktori penting:
- `public/` front controller (`index.php`) dan aset publik (`assets/`)
- `app/` kode sumber (Core, Config, dan modul MVC)
- `app/Views/layouts/main.php` layout utama yang memuat template UI dari `assets`

Konfigurasi:
- `APP_BASE_URL` dapat diset di environment (lihat `docker-compose.yml`)
- Variabel DB: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`

Routing dasar:
- `/` dashboard (SistemDataMaster)
- `/sims/surat-masuk`, `/sims/surat-keluar`, `/sims/laporan-agenda`
- `/keuangan`, `/keuangan/invoice-gaji`, `/keuangan/laporan`
- `/program-kerja`, `/program-kerja/anggaran`

Catatan:
- Ini adalah kerangka MVC modular, siap dikembangkan sesuai BRD.
- Keamanan (CSRF, XSS, Turnstile) dan fitur fungsional akan ditambahkan saat implementasi modul.