# Global Supply Chain Risk Intelligence Platform

Aplikasi Laravel untuk memantau risiko rantai pasok global berdasarkan data negara, cuaca, ekonomi, nilai tukar, pelabuhan, dan berita. Aplikasi menyediakan dashboard admin dan user, peta Leaflet, watchlist, perbandingan negara, analisis sentimen, serta perhitungan skor risiko.

## Fitur utama

- Dashboard khusus admin dan user
- Data negara dan informasi ekonomi
- Peta cuaca global dari Open-Meteo
- Peta pelabuhan dari World Port Index
- Nilai tukar mata uang
- Berita dan analisis sentimen
- Skor risiko dan perbandingan negara
- Watchlist pengguna
- Pilihan bahasa Indonesia dan Inggris
- Sinkronisasi API terjadwal

## Kebutuhan sistem

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- Ekstensi PHP: `curl`, `mbstring`, `openssl`, `pdo`, `pdo_sqlite` atau `pdo_mysql`
- Git

Untuk Windows, proyek dapat dijalankan menggunakan XAMPP selama versi PHP memenuhi persyaratan.

## Instalasi dari GitHub

### 1. Clone repository

```bash
git clone URL_REPOSITORY_ANDA
cd global-risk-platform
```

Ganti `URL_REPOSITORY_ANDA` dengan URL repository GitHub proyek ini.

### 2. Instal dependensi

```bash
composer install
npm install
```

### 3. Buat file konfigurasi

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Linux/macOS:

```bash
cp .env.example .env
```

Kemudian buat application key:

```bash
php artisan key:generate
```

## Konfigurasi database

### Pilihan A: MySQL atau MariaDB

Konfigurasi bawaan mengikuti spesifikasi tugas dan menggunakan MySQL. Buat database kosong bernama `global_risk_platform`, lalu pastikan `.env` berisi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=global_risk_platform
DB_USERNAME=root
DB_PASSWORD=
```

### Pilihan B: SQLite untuk pengembangan lokal

SQLite dapat digunakan untuk pengembangan ringan.

Windows PowerShell:

```powershell
New-Item database/database.sqlite -ItemType File -Force
```

Linux/macOS:

```bash
touch database/database.sqlite
```

Ubah `.env` menjadi:

```env
DB_CONNECTION=sqlite
```

## Konfigurasi API

Tambahkan konfigurasi berikut pada `.env`:

```env
REST_COUNTRIES_URL=https://api.restcountries.com/countries/v5
REST_COUNTRIES_API_KEY=

OPEN_METEO_URL=https://api.open-meteo.com/v1/forecast
WORLD_BANK_URL=https://api.worldbank.org/v2
EXCHANGE_RATE_URL=https://open.er-api.com/v6/latest

GNEWS_URL=https://gnews.io/api/v4
GNEWS_API_KEY=MASUKKAN_API_KEY_GNEWS

WORLD_PORT_API_URL=https://services-eu1.arcgis.com/BuS9rtTsYEV5C0xh/arcgis/rest/services/World_Port_Index/FeatureServer/0/query
```

Keterangan:

- Open-Meteo, World Bank, ExchangeRate, dan World Port Index tidak memerlukan API key untuk konfigurasi yang digunakan proyek ini.
- GNews memerlukan API key agar sinkronisasi berita dapat digunakan.
- REST Countries v5 dapat menggunakan API key. Jika key tidak tersedia, aplikasi mencoba dataset negara publik sebagai fallback.
- Jangan memasukkan API key asli ke `.env.example` atau melakukan commit file `.env` ke GitHub.

Setelah mengubah `.env`, bersihkan cache konfigurasi:

```bash
php artisan optimize:clear
```

## Menyiapkan database dan data awal

Jalankan migrasi:

```bash
php artisan migrate
```

Ambil data negara terlebih dahulu:

```bash
php artisan monitoring:sync countries
```

Kemudian buat akun awal dan kamus sentimen:

```bash
php artisan db:seed
```

Sinkronkan seluruh sumber data:

```bash
php artisan monitoring:sync all
```

Sinkronisasi pertama dapat membutuhkan waktu karena aplikasi mengambil data dari beberapa API eksternal.

## Build frontend

Untuk penggunaan biasa:

```bash
npm run build
```

Untuk pengembangan dengan pemantauan perubahan file:

```bash
npm run dev
```

## Menjalankan aplikasi

### Windows dengan sinkronisasi otomatis

```powershell
.\start-realtime.ps1
```

Perintah tersebut menjalankan server di `http://127.0.0.1:8000` dan mengaktifkan scheduler Laravel.

### Semua sistem secara manual

Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
php artisan schedule:work
```

Buka `http://127.0.0.1:8000` pada browser.

## Akun awal

Seeder membuat akun berikut:

| Peran | Email | Password |
|---|---|---|
| Admin | `admin@example.com` | `password` |
| User | `user@example.com` | `password` |

Ganti password akun tersebut setelah login, terutama jika aplikasi akan digunakan di luar komputer lokal.

Pengguna baru juga dapat membuat akun melalui halaman pendaftaran.

## Perbedaan hak akses

| Fitur | Admin | User |
|---|:---:|:---:|
| Melihat negara, peta, ekonomi, kurs, berita, dan risiko | Ya | Ya |
| Membandingkan negara | Ya | Ya |
| Menambah negara ke watchlist pribadi | Tidak | Ya |
| Menambah, mengubah, dan menghapus master data | Ya | Tidak |
| Menjalankan sinkronisasi API dari antarmuka | Ya | Tidak |
| Menghitung ulang skor risiko | Ya | Tidak |
| Mengelola pengguna dan perannya | Ya | Tidak |
| Mengelola artikel analisis | Ya | Tidak |

Pembatasan admin diterapkan melalui middleware pada route, sehingga URL pengelolaan tetap tidak dapat diakses oleh user biasa.

## Perintah sinkronisasi

```bash
php artisan monitoring:sync countries
php artisan monitoring:sync ports
php artisan monitoring:sync weather
php artisan monitoring:sync economy
php artisan monitoring:sync exchange
php artisan monitoring:sync news
php artisan monitoring:sync risk
php artisan monitoring:sync all
```

Cuaca, ekonomi, kurs, dan risiko terjadwal diproses untuk negara yang masuk watchlist. Jika belum ada watchlist, Indonesia digunakan sebagai default.

Jadwal bawaan:

| Data | Jadwal |
|---|---|
| Cuaca | Setiap 10 menit |
| Skor risiko | Setiap 10 menit |
| Nilai tukar | Setiap 15 menit |
| Berita | Setiap jam |
| Ekonomi | Setiap hari pukul 01.00 |
| Negara | Setiap hari pukul 02.00 |
| Pelabuhan | Setiap Senin pukul 03.00 |

## Menjalankan pengujian

```bash
php artisan test
```

## Pemecahan masalah

### Perubahan `.env` tidak terbaca

```bash
php artisan optimize:clear
```

### Data masih kosong

Pastikan komputer terhubung ke internet, kemudian jalankan:

```bash
php artisan monitoring:sync all
```

Tambahkan negara ke watchlist agar data negara tersebut ikut diperbarui oleh scheduler.

### Error `cURL error 6` atau `cURL error 28`

Error tersebut biasanya berarti DNS tidak dapat menemukan host atau koneksi mengalami timeout. Periksa koneksi internet, firewall, proxy, DNS, dan pastikan ekstensi PHP cURL aktif.

### Tampilan frontend tidak berubah

```bash
npm run build
php artisan view:clear
```

Kemudian refresh browser menggunakan `Ctrl + F5`.

### Mengulang database dari awal

Perintah berikut menghapus seluruh data lokal:

```bash
php artisan migrate:fresh
php artisan monitoring:sync countries
php artisan db:seed
php artisan monitoring:sync all
```

Gunakan hanya jika data lama memang tidak diperlukan.

## Keamanan

- Simpan API key dan password hanya di `.env`.
- Jangan commit `.env`, database lokal, atau kredensial ke GitHub.
- Nonaktifkan `APP_DEBUG` pada production.
- Ganti akun dan password bawaan sebelum deployment.

Contoh production:

```env
APP_ENV=production
APP_DEBUG=false
```

## Lisensi

Proyek ini menggunakan Laravel yang tersedia di bawah lisensi MIT.
