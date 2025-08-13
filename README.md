
# Business Requirements Document (BRD) - Platform Multi-Sistem Terintegrasi

## 1\. Pendahuluan

Dokumen ini menguraikan persyaratan bisnis untuk pengembangan sebuah **Platform Multi-Sistem Terintegrasi**. Platform ini akan menyatukan empat sistem utama—Sistem Manajemen Data Master, Sistem Informasi Manajemen Surat, Sistem Manajemen Keuangan, dan Sistem Program Kerja & Anggaran—ke dalam satu ekosistem digital yang terpadu. Sistem ini akan dibangun dengan **struktur MVC yang modular** untuk kemudahan manajemen dan *debugging*.

  * **Nama Proyek:** Platform Multi-Sistem Terintegrasi Lembaga
  * **Tanggal Pembuatan:** 11 Agustus 2025
  * **Versi:** 2.6 (Pembaruan Manajemen Lembaga & Jabatan)
  * **Target Teknologi:** PHP 8.3 ke atas (dengan struktur MVC modular), MySQL
  * **Target Desain:**  - [C:\laragon\www\SIMS\asset]
  * **Stakeholder Utama:** Kepala Lembaga, Direktur Keuangan, Koordinator Program, Sekretaris, Petugas Administrasi & Keuangan, Tim IT

-----

## 2\. Prinsip Arsitektur Utama

### Data Terpusat dan Single Sign-On (SSO)

Ini adalah prinsip inti dari platform. **Sistem Manajemen Data Master** akan berfungsi sebagai **sumber kebenaran tunggal (*single source of truth*)**. Semua data pengguna (dosen, karyawan, dan perannya di setiap lembaga) akan disimpan di sini. Melalui **SSO**, setiap sistem (Surat, Keuangan, Program Kerja) akan mengakses data dari pusat ini, memastikan konsistensi dan integritas data tanpa perlu duplikasi.

### Struktur Navigasi Sidebar

Antarmuka pengguna akan dirancang dengan sidebar yang menampilkan struktur hierarki untuk kemudahan navigasi antar sistem dan modul.

  * **SIMS** (Sistem Informasi Manajemen Surat)
      * Surat Masuk
      * Surat Keluar
      * Laporan Agenda
  * **Keuangan**
      * Manajemen Keuangan
      * Invoice Gaji
      * Laporan Keuangan
  * **Program Kerja**
      * Program Kerja
      * Manajemen Anggaran

-----

## 3\. Sistem Manajemen Data Master

Ini adalah inti dari platform, berfungsi sebagai sumber kebenaran tunggal untuk semua data identitas dan afiliasi. Semua sistem lain akan mengambil data dari sini.

  * **Manajemen Data Dosen, Karyawan, & Lembaga (CRUD):**
      * **Data Dosen:** CRUD data dosen, termasuk detail pribadi, riwayat pendidikan, dan jabatan.
      * **Data Karyawan:** CRUD data karyawan non-dosen, termasuk detail pribadi dan jabatan.
      * **Data Lembaga:** CRUD data lembaga/unit kerja, termasuk nama, deskripsi, dan logo. Sistem harus dapat menandai lembaga mana yang memiliki peran sebagai "lembaga keuangan".
      * **Import & Export Data Massal:**
          * **Import Massal:** Fitur untuk mengunggah dan mengimpor data dosen, karyawan, atau lembaga dalam jumlah besar dari file Excel (.xlsx, .xls) atau CSV dengan format yang telah ditentukan.
          * **Export Massal:** Fitur untuk mengekspor seluruh data master yang sudah ada ke format Excel atau CSV.
  * **Manajemen Struktur Lembaga & Jabatan:**
      * Admin dapat membuat **struktur hierarki lembaga** (misal: Rektorat -\> Fakultas -\> Jurusan -\> Program Studi).
      * Admin dapat membuat daftar **jabatan** yang ada di setiap lembaga (misal: Kepala Jurusan, Sekretaris Jurusan, Koordinator Program).
      * Admin dapat **menugaskan satu user** (dosen atau karyawan) sebagai **penanggung jawab** dari jabatan tertentu di lembaga tertentu.
      * **Login Penanggung Jawab:** Saat user yang ditugaskan sebagai penanggung jawab lembaga login, sistem secara otomatis akan memberinya akses dan hak kelola untuk semua data yang terkait dengan lembaga tersebut di semua sistem. Misalnya, penanggung jawab lembaga di Sistem Surat hanya bisa melihat surat masuk/keluar dari lembaganya.
  * **Fitur Hubungan & Peran Ganda:**
      * Sistem akan memungkinkan satu individu (ID unik) untuk dikaitkan dengan **berbagai peran** (Dosen, Karyawan) dan **berbagai lembaga**.
      * Sinkronisasi Data: Data master dari sistem ini akan menjadi referensi utama.

-----

## 4\. Sistem Informasi Manajemen Surat

Sistem ini akan mengelola seluruh siklus hidup surat-menyurat dengan fitur-fitur berikut:

  * **Fitur Dasar:**
      * Buat surat dengan nomor **statis dan dinamis** per lembaga.
      * **Template header dan footer** surat yang bisa disesuaikan.
      * Cetak **disposisi surat masuk**.
      * **Upload lampiran** dalam format .JPG, .PNG, .DOC, .DOCX, dan .PDF.
      * Galeri file lampiran yang terunggah.
      * Import kode klasifikasi surat format **.CSV**.
  * **Fitur Arsip & Laporan:**
      * **Arsip Surat Tahunan:** Sistem akan secara otomatis mengarsip surat masuk dan keluar per tahun.
      * **Filter & Download Data:** Pengguna dapat memfilter data surat berdasarkan tanggal, klasifikasi, atau status, dan **mendownload semua data yang difilter** dalam format yang ditentukan (misalnya, Excel atau PDF).

-----

## 5\. Sistem Manajemen Keuangan

Sistem ini akan mengelola seluruh transaksi keuangan keluar-masuk lembaga dengan fitur-fitur yang terintegrasi. **Sistem ini hanya dapat diakses oleh pengguna yang merupakan bagian dari lembaga keuangan.**

  * **Manajemen Keuangan:**
      * Pencatatan **transaksi keuangan keluar-masuk** secara rinci.
      * Perhitungan dan pembaruan saldo keuangan secara **otomatis**.
      * Kategori dan klasifikasi transaksi untuk pelaporan yang terstruktur.
  * **Manajemen Invoice Gaji:**
      * Pembuatan **invoice gaji** untuk dosen dan karyawan yang datanya diambil dari Sistem Manajemen Data Master.
      * Informasi *invoice* mencakup gaji pokok, tunjangan, dan potongan.
      * **Pengiriman Invoice Otomatis:** Integrasi dengan **PHPMailer** untuk mengirimkan *invoice* gaji secara otomatis ke email masing-masing dosen dan karyawan.
  * **Laporan Keuangan:**
      * Membuat laporan keuangan dasar (laba/rugi, arus kas) berdasarkan data yang tercatat.
      * Laporan dapat difilter berdasarkan tanggal atau kategori.

-----

## 6\. Sistem Program Kerja & Anggaran

Sistem ini berfungsi untuk perencanaan dan pengelolaan program kerja beserta anggarannya.

  * **Manajemen Program Kerja (Proker):**
      * Setiap lembaga (yang datanya diambil dari Sistem Manajemen Data Master) dapat membuat, mengedit, dan mengelola daftar program kerja tahunan.
      * Setiap proker mencakup detail seperti nama program, deskripsi, penanggung jawab, dan periode pelaksanaan.
      * **Penanggung Jawab Proker:** Penanggung jawab program kerja **wajib dipilih dari daftar dosen** yang tersedia di Sistem Manajemen Data Master.
  * **Manajemen Anggaran:**
      * Setiap proker memiliki alokasi anggaran yang ditetapkan.
      * Alokasi anggaran ini **tersinkronisasi secara langsung dengan Sistem Manajemen Keuangan**.
      * Sistem dapat melacak penggunaan anggaran per proker, menampilkan status (misalnya, sisa anggaran, persentase penggunaan).
  * **Laporan Proker & Anggaran:**
      * Laporan kinerja program kerja dan penggunaan anggaran per lembaga.
      * Laporan ini dapat diakses oleh pihak yang berwenang (misalnya, Direktur/Dekan) untuk monitoring dan evaluasi.

-----

## 7\. Struktur Proyek & Kebutuhan Non-Fungsional

### Struktur Proyek PHP Native dengan MVC Modular

Kode proyek akan diorganisir dalam struktur modular untuk kemudahan *debugging* dan pemeliharaan. Setiap sistem akan memiliki direktori MVC-nya sendiri.

Contoh struktur direktori:

```
├── app/
│   ├── Core/                
│   ├── Config/              
│   ├── SistemDataMaster/    
│   │   ├── Models/
│   │   ├── Views/
│   │   └── Controllers/
│   ├── SistemSurat/         
│   │   ├── Models/
│   │   ├── Views/
│   │   └── Controllers/
│   ├── Keuangan/            
│   │   ├── Models/
│   │   ├── Views/
│   │   └── Controllers/
│   └── ProgramKerja/        
│       ├── Models/
│       ├── Views/
│       └── Controllers/
└── public/
    └── assets/
```

### Kebutuhan Non-Fungsional

  * **Keamanan:**
      * Pencegahan **SQL Injection**, **XSS**, **CSRF**, dan serangan *brute-force* (Cloudflare Turnstile di login).
      * Keamanan unggahan file dan pencegahan **Bypass Webshell**.
  * **Pemrograman:** Kode harus ditulis dengan **best practices PHP 8.3 ke atas** yang terstruktur, modern, dan terdokumentasi.
  * **Usability:** Antarmuka pengguna harus intuitif dan konsisten di seluruh platform, menggunakan tata letak dan komponen dari **Sneat Bootstrap HTML Admin Template Free**.
  * **Skalabilitas & Performa:** Arsitektur sistem harus mampu menangani pertumbuhan data yang pesat dari semua modul tanpa mengorbankan kecepatan dan stabilitas.
  * **Keandalan:** Fitur *backup* dan *restore* database terpusat harus fungsional untuk mencegah kehilangan data.

