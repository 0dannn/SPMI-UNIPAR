# Sistem Penjaminan Mutu Internal (SPMI) V3

Sistem Penjaminan Mutu Internal (SPMI) V3 adalah platform web mutakhir berbasis Laravel 11 yang dirancang secara khusus untuk memfasilitasi dan mendigitalisasi seluruh siklus **PPEPP** (Penetapan, Pelaksanaan, Evaluasi, Pengendalian, dan Peningkatan) dalam lingkup institusi akademik atau perguruan tinggi. Sistem ini menyediakan dasbor eksekutif canggih, integrasi otomatisasi penugasan audit, dan pelaporan terpadu.

## 🚀 Teknologi Pendukung (Tech Stack)
- **Backend:** Laravel 11.x (PHP 8.2)
- **Frontend:** Laravel Blade, Alpine.js, Tailwind CSS (Vite Build)
- **Database:** MySQL 8.0 / SQLite (Dukungan ORM Eloquent)
- **Security:** Spatie Permission, Middleware Lintas Otorisasi, Laravel Sanctum, CSRF Protection
- **Laporan & Visulialisasi:** DomPDF (Ekspor Laporan PDF), Chart.js (Grafik Interaktif)

## ⚙️ Prerequisites (Persyaratan Sistem)
Sebelum menjalankan proyek ini, pastikan mesin Anda telah menginstal dan mengonfigurasi perangkat lunak berikut:
- PHP >= 8.2 (Ekstensi PCOV atau Xdebug disarankan terinstalasi untuk proses uji *testing coverage*)
- Composer >= 2.7
- Node.js >= 20.0
- MySQL Server >= 8.0 (atau ekosistem SQLite untuk pengujian ringkas skala lokal)

## 🛠️ Panduan Instalasi Lokal

1. **Clone Repositori & Masuk ke Folder Proyek**
   ```bash
   git clone <url-repository>
   cd spmi-website
   ```

2. **Instalasi Dependencies Backend & Frontend**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment Database**
   Gandakan file konfigurasi `.env.example` ke dalam rupa `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` di teks editor, kemudian sesuaikan parameter kredensial database Anda (DB_CONNECTION, DB_DATABASE, dll).

4. **Generate Application Key (Kunci Enkripsi)**
   Sistem butuh kunci sidik jari aplikasi untuk menjaga privasi sesi pengguna.
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Database Seeder**
   Sistem telah dilengkapi dengan data perancah (seeder) berupa *Roles* dan pengguna awalan.
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Tautkan Direktori Penyimpanan Publik (Symlink)**
   Demi kerahasiaan (*Security Compliance*), foto pengguna dan bukti fisik memiliki jalur akses asinkron.
   ```bash
   php artisan storage:link
   ```

7. **Compile Asset Antarmuka (UI) & Nyalakan Server**
   ```bash
   npm run build
   php artisan serve
   ```
   Aplikasi siap beroperasi pada akses tautan `http://127.0.0.1:8000`.

## 📂 Peta Struktur Direktori Utama
- `app/Http/Controllers` - Menampung pengendali logika alur (seperti `AuditAmiController`, `DashboardController`).
- `app/Services` - Lapisan perantara untuk logika kompleks seperti *Notifikasi* dan algoritma sekuritas *File Upload*.
- `resources/views` - Ratusan komponen antarmuka (UI) yang dikonstruksi berbasis Blade Engine bersandingan dengan utilitas Tailwind CSS.
- `database/migrations` - Keseluruhan 15 tapak struktur pangkalan data primer SPMI.
- `tests/Feature` - Skenario pengujian (*Automated Script Testing*) bertenaga PHPUnit.

## ✉️ Kontak Pengembang
Perangkat Lunak SPMI dikembangkan sebagai Pemenuhan Integritas Karya / Skripsi Tugas Akhir.  
**Insinyur Pengembang:** Jordan / Antigravity Agent (Google AI)
