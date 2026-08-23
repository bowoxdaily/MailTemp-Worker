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

## Quick Install (Recommended)

Setelah clone dan `composer install`, jalankan wizard interaktif:

```bash
php artisan app:install
```

Wizard akan memandu Anda untuk:

- Membuat file `.env` dan generate application key
- Mengkonfigurasi koneksi database (MySQL/PostgreSQL/SQLite)
- Menjalankan migration
- Membuat admin user
- Menambahkan domain email pertama
- Install & build frontend assets

Setelah selesai, wizard menampilkan summary dan next steps. Wizard juga memeriksa koneksi Redis, memvalidasi domain, dan menampilkan instruksi scheduler sesuai OS. Redis boleh belum aktif saat instalasi lokal, tetapi wajib tersedia untuk queue, cache, rate limiter, dan penerimaan email production.

---

## Setup Wizard (Web-Based)

Ketika pertama kali mengakses aplikasi di browser, Anda akan otomatis diarahkan ke **Setup Wizard** jika belum ada admin user.

Setup wizard terdiri dari 3 langkah:

1. **Create Admin Account** (`/setup`) — Buat akun admin pertama (nama, email, password).
2. **Deploy Cloudflare Worker** (`/setup/cloudflare`) — Masukkan Cloudflare API Token dan Account ID, lalu deploy worker langsung dari browser.
3. **Setup Complete** (`/setup/complete`) — Konfirmasi bahwa setup berhasil, dengan link ke Admin Panel dan halaman utama.

> **Catatan:** Setelah admin dibuat, halaman setup tidak bisa diakses lagi dan akan redirect ke Admin Dashboard.

---

## Panduan Instalasi Manual

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

Infrastruktur email menggunakan Cloudflare Workers untuk memproses email masuk dan meneruskannya ke backend Laravel. Worker name: **`email-worker`**.

### 1. Buat Cloudflare API Token

1. Buka [Cloudflare API Tokens](https://dash.cloudflare.com/profile/api-tokens)
2. Klik **Create Token** → **Create Custom Token**
3. Set permissions:
    - **Account** → `Workers Scripts` → **Edit**
    - **Account** → `Account Settings` → **Read**
4. Account Resources → pilih account Anda
5. Klik **Continue to summary** → **Create Token**
6. **Copy token** — simpan baik-baik, hanya ditampilkan sekali

### 2. Dapatkan Account ID

1. Buka [Cloudflare Dashboard](https://dash.cloudflare.com/)
2. Pilih domain Anda
3. Di halaman **Overview**, scroll ke bawah di sidebar kanan
4. Copy **Account ID** (bukan Zone ID)

### 3. Deploy Worker

Deploy bisa dilakukan via **Setup Wizard** (web) atau **CLI**:

**Via Web (Setup Wizard):**

- Buka aplikasi di browser → ikuti Setup Wizard → masukkan API Token & Account ID → klik Deploy

**Via CLI:**

```bash
php artisan cloudflare:setup --deploy
```

Worker akan otomatis:

- Install dependencies (`npm install`)
- Set secrets `BACKEND_URL` dan `WORKER_SECRET` di Cloudflare
- Deploy worker `email-worker` ke akun Cloudflare Anda

### 4. Setup Email Routing di Cloudflare

Setelah worker ter-deploy, Anda perlu mengkonfigurasi Email Routing:

1. Buka **Cloudflare Dashboard** → pilih domain → **Email** → **Email Routing**
2. Aktifkan **Email Routing** jika belum aktif
3. Cloudflare akan otomatis menambahkan DNS records berikut:

    | Type | Name | Content                                      | Priority |
    | ---- | ---- | -------------------------------------------- | -------- |
    | MX   | `@`  | `route1.mx.cloudflare.net`                   | 69       |
    | MX   | `@`  | `route2.mx.cloudflare.net`                   | 4        |
    | MX   | `@`  | `route3.mx.cloudflare.net`                   | 36       |
    | TXT  | `@`  | `v=spf1 include:_spf.mx.cloudflare.net ~all` | —        |

4. Buka tab **Email Workers** → klik **Create** atau pilih worker `email-worker`
5. Set **Catch-All** rule → action: **Send to Worker** → pilih `email-worker`

> **Penting:** Catch-All harus mengarah ke worker `email-worker` agar semua email masuk diproses.

### 5. Verifikasi Worker dan Email Masuk

1. Buka `https://<worker-subdomain>.workers.dev/health`.
2. Pastikan response JSON berisi `"status":"ok"`, `"backend_configured":true`, dan `"secret_configured":true`.
3. Kirim email test ke alamat temporary yang aktif.
4. Refresh inbox dan pastikan email tampil.

Jika `/health` gagal, periksa secret `BACKEND_URL`, `WORKER_SECRET`, deployment Worker, dan URL backend. Jika `/health` berhasil tetapi email tidak masuk, periksa Catch-All Email Routing dan queue worker Laravel.

### Referensi Tambahan

👉 **[Panduan Detail Cloudflare Worker](./cloudflare/README.md)**

---

## Deploy ke Production (VPS)

### 1. Upload Project ke Server

```bash
# Clone dari repository
git clone https://github.com/bowoxdaily/MailTemp-Worker.git
cd MailTemp-Worker

# Atau jika sudah ada, pull update terbaru
git pull origin main
```

### 2. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
```

### 4. Jalankan Migration

```bash
php artisan migrate --force
```

### 5. Optimasi Laravel

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set Permissions

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 7. Konfigurasi Nginx

Contoh config Nginx (`/etc/nginx/sites-available/tempmail`):

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/MailTemp-Worker/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Aktifkan site dan restart:

```bash
sudo ln -s /etc/nginx/sites-available/tempmail /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

> Untuk HTTPS, gunakan [Certbot](https://certbot.eff.org/): `sudo certbot --nginx -d yourdomain.com`

### 8. Setup Crontab (Scheduler)

Buka crontab editor:

```bash
crontab -e
```

Tambahkan baris berikut:

```cron
* * * * * cd /var/www/MailTemp-Worker && php artisan schedule:run >> /dev/null 2>&1
```

Scheduler ini menjalankan pembersihan email kedaluwarsa dan tugas terjadwal lainnya secara otomatis setiap menit.

### 9. Setup Supervisor (Queue Worker)

Buat config Supervisor (`/etc/supervisor/conf.d/tempmail-worker.conf`):

```ini
[program:tempmail-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/MailTemp-Worker/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/MailTemp-Worker/storage/logs/worker.log
stopwaitsecs=3600
```

Aktifkan:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start tempmail-worker:*
```

### 10. Setup Wizard

Setelah semua selesai, buka `https://yourdomain.com` di browser. Anda akan otomatis diarahkan ke Setup Wizard untuk:

1. Membuat admin account
2. Deploy Cloudflare Worker
3. Menyelesaikan konfigurasi

---

## Menjalankan Test Suite

Project ini menggunakan Pest untuk testing. Anda dapat memverifikasi seluruh fungsionalitas backend berjalan dengan baik menggunakan perintah:

```bash
php artisan test
```
