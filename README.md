# Cloudflare Temp Mail

Layanan temporary email gratis dan realtime menggunakan Laravel 12 di sisi backend dan Cloudflare Email Routing + Cloudflare Worker di sisi infrastruktur email.

## Fitur Utama

- **Instan & Tanpa Registrasi**: Generate alamat email acak secara instan langsung saat membuka situs.
- **Inbox Realtime**: Email yang dikirim langsung masuk ke inbox tanpa delay tinggi.
- **Custom Username & Domain**: Buat alamat email kustom dengan domain aktif yang tersedia.
- **Sistem Kedaluwarsa Otomatis**: Alamat email dan pesan terhapus secara terjadwal setelah masa berlaku habis.
- **Admin Panel**: Kelola branding, domain, pantau statistik, konfigurasi limit, dan deployment Cloudflare Worker.

---

## Persyaratan Sistem

Pastikan server atau komputer lokal Anda telah menginstal:

- PHP 8.2 atau lebih baru (disarankan PHP 8.3+)
- Composer
- Node.js & NPM
- MySQL atau PostgreSQL
- Redis (untuk queue, caching, dan rate limiter)

---

## Panduan Instalasi Lokal

### 1. Clone Project

```bash
git clone https://github.com/bowoxdaily/MailTemp-Worker.git
cd EmailTemp
```

### 2. Install Dependensi Backend

```bash
composer install
```

### 3. Konfigurasi Environment File

Salin file `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Buka file `.env` dan atur koneksi database dan Redis Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_db
DB_PASSWORD=password_db

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Generate application key:

```bash
php artisan key:generate
```

### 4. Jalankan Database Migration & Seeder

```bash
php artisan migrate --seed
```

### 5. Install & Build Dependensi Frontend

```bash
npm install
npm run build
```

---

## Menjalankan Server Development

### 1. Jalankan Aplikasi Laravel

```bash
php artisan serve
```

### 2. Jalankan Frontend (Vite HMR)

```bash
npm run dev
```

### 3. Jalankan Queue Worker (Untuk memproses email masuk & cleanup)

```bash
php artisan queue:work
```

### 4. Jalankan Scheduler (Untuk membersihkan email yang kedaluwarsa secara otomatis)

```bash
php artisan schedule:work
```

---

## Panduan Integrasi Cloudflare Email

Infrastruktur email menggunakan Cloudflare Workers untuk memproses email masuk dan meneruskannya ke backend Laravel.

Untuk panduan lengkap setup Cloudflare credentials, deployment worker via Artisan, dan konfigurasi Cloudflare Dashboard, silakan baca dokumentasi khusus kami di:

👉 **[Panduan Setup Cloudflare Worker](./cloudflare/README.md)**

---

## Menjalankan Test Suite

Project ini menggunakan Pest untuk testing. Anda dapat memverifikasi seluruh fungsionalitas backend berjalan dengan baik menggunakan perintah:

```bash
php artisan test
```
