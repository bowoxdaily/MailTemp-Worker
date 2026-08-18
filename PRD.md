# PRD.md

# Cloudflare Temp Mail

## 1. Nama Produk

Cloudflare Temp Mail

Website temporary email gratis yang memungkinkan pengguna membuat alamat email sementara tanpa registrasi.

## 2. Tujuan

Membuat layanan temporary email yang:

- Bisa digunakan tanpa login.
- Bisa membuat email secara instan.
- Bisa menerima email secara realtime.
- Bisa membaca OTP dan email verifikasi.
- Memiliki masa berlaku otomatis.
- Menghapus data yang sudah expired.
- Menggunakan Cloudflare sebagai infrastruktur email.
- Menghasilkan pendapatan melalui iklan.
- Cepat dan ringan di perangkat mobile maupun desktop.

## 3. Target Pengguna

- Pengguna yang membutuhkan email sementara.
- Developer untuk testing email.
- QA tester.
- Pengguna yang tidak ingin memberikan email utama.
- Pengguna yang membutuhkan inbox sementara.

## 4. Konsep Utama

User membuka website.

Sistem otomatis membuat alamat email sementara.

Contoh:

`x8k29f@domainanda.com`

User dapat menyalin alamat tersebut.

Email yang dikirim ke alamat tersebut masuk ke inbox temporary email.

Setelah masa berlaku habis, alamat dan seluruh email dihapus.

## 5. User Flow

### Generate Email

```text
User membuka website
        ↓
Generate random email
        ↓
Email ditampilkan
        ↓
User copy email
```

### Receive Email

```text
Email dikirim ke alamat temporary
        ↓
Cloudflare menerima email
        ↓
Cloudflare Worker memproses email
        ↓
Backend menerima email
        ↓
Simpan inbox
        ↓
Frontend mendapatkan email baru
```

### Expired

```text
Email mencapai waktu expired
        ↓
Disable alamat email
        ↓
Hapus inbox
        ↓
Hapus attachment
        ↓
Bersihkan database
```

## 6. Fitur MVP

### 6.1 Generate Email

Sistem harus dapat:

- Membuat alamat email random.
- Menggunakan username random.
- Memilih domain aktif.
- Memastikan username tidak sedang digunakan.
- Menampilkan waktu expired.

Contoh:

```text
Your temporary email

k82jd91@domain.com

Expires in:
09:42

[ Copy ]
[ Refresh ]
[ Delete ]
```

### 6.2 Inbox

Inbox menampilkan:

- Sender.
- Subject.
- Date.
- Status read/unread.
- Email body.

### 6.3 Email Detail

User dapat membuka email.

Informasi:

- From.
- To.
- Subject.
- Date.
- Body.
- HTML email.
- Plain text email.
- Attachment jika didukung.

### 6.4 Copy Email

Tombol copy harus tersedia.

Setelah berhasil, tampilkan status `Copied!`.

### 6.5 Refresh Inbox

User dapat melakukan refresh secara manual.

Sistem juga melakukan polling atau realtime update.

Target email baru tampil maksimal beberapa detik setelah diterima backend.

### 6.6 Delete Email

User dapat menghapus:

- Satu email.
- Seluruh inbox.
- Temporary address.

### 6.7 Expiration

Default:

```text
10 menit
```

Pilihan:

```text
10 Minutes
30 Minutes
1 Hour
```

Untuk MVP, 10 menit menjadi default.

## 7. Email Infrastructure

Cloudflare digunakan sebagai email receiving layer.

Flow:

```text
Internet
   ↓
Cloudflare Email
   ↓
Email Worker
   ↓
Backend API
   ↓
Redis
   ↓
MySQL
   ↓
Frontend
```

Worker bertugas:

- Menerima email.
- Membaca recipient.
- Mengambil username temporary.
- Melakukan validasi.
- Mengirim email ke backend.
- Menolak alamat yang sudah expired.

## 8. Backend

Gunakan Laravel.

Tanggung jawab Laravel:

- Generate email.
- Validasi temporary address.
- Inbox management.
- Email parsing.
- Expiration.
- Domain management.
- Rate limiting.
- API.
- Admin panel.
- Statistik.

## 9. Database

### temporary_emails

```text
id
address
username
domain_id
token
status
expires_at
created_at
updated_at
```

### domains

```text
id
domain
status
created_at
updated_at
```

### emails

```text
id
temporary_email_id
message_id
sender
recipient
subject
body_text
body_html
received_at
created_at
```

### attachments

```text
id
email_id
filename
mime_type
size
storage_path
created_at
```

### blocked_senders

```text
id
email
reason
status
created_at
```

### blocked_domains

```text
id
domain
reason
status
created_at
```

## 10. Redis

Redis digunakan untuk:

- Temporary email session.
- Inbox cache.
- Rate limit.
- Email notification.
- Temporary locks.
- Realtime event.

Data yang membutuhkan persistence tetap disimpan di MySQL.

## 11. API

### Generate Email

```http
POST /api/email/generate
```

Response:

```json
{
  "success": true,
  "email": "x82jd91@domain.com",
  "expires_at": "2026-08-18T15:30:00+07:00"
}
```

### Get Inbox

```http
GET /api/email/{token}/inbox
```

### Get Email

```http
GET /api/email/{token}/messages/{id}
```

### Delete Email

```http
DELETE /api/email/{token}/messages/{id}
```

### Delete Temporary Address

```http
DELETE /api/email/{token}
```

### Receive Email

Endpoint khusus Cloudflare Worker:

```http
POST /api/internal/email/receive
```

Endpoint ini harus menggunakan authentication khusus.

## 12. Security

Sistem harus memiliki:

- Rate limiting.
- API authentication.
- Request signature.
- CSRF protection.
- Input validation.
- HTML sanitization.
- XSS protection.
- SQL injection protection.
- Attachment validation.
- Maximum email size.
- Maximum attachment size.
- IP rate limit.
- Domain blocking.
- Sender blocking.

Email HTML harus disanitasi sebelum ditampilkan kepada pengguna.

Jangan langsung merender HTML email tanpa sanitization.

## 13. Anti Abuse

Karena layanan bersifat public, sistem harus memiliki perlindungan abuse.

Implementasi:

```text
IP Rate Limit
↓
Generate Limit
↓
Email Receive Limit
↓
Attachment Limit
↓
Spam Detection
```

Contoh:

```text
Maximum 10 email address / IP / hour
```

Nilainya dapat diubah melalui Admin Panel.

## 14. Admin Panel

Admin dapat melihat dashboard:

```text
Total Emails
Today's Emails
Active Temporary Emails
Expired Emails
Blocked Requests
Traffic
```

### Domain Management

Admin dapat:

- Tambah domain.
- Hapus domain.
- Aktifkan domain.
- Nonaktifkan domain.

### Email Monitoring

Admin dapat melihat:

- Sender.
- Recipient.
- Subject.
- Received time.
- Size.
- Status.

Akses terhadap isi email harus dibatasi dan dicatat untuk menjaga privasi.

### Abuse Management

Admin dapat:

- Block IP.
- Block sender.
- Block domain.
- Set rate limit.

## 15. Advertising

Model monetisasi:

```text
Free Temp Mail
       ↓
Traffic
       ↓
Advertising
       ↓
Revenue
```

Area iklan:

```text
Header
↓
Email Generator
↓
Inbox
↓
Footer
```

Jangan menempatkan iklan di area tombol Copy atau isi OTP karena dapat mengganggu usability.

## 16. SEO

Target keyword:

- temp mail
- temporary email
- temporary email generator
- disposable email
- 10 minute mail
- fake email
- temporary inbox
- temp email address

Halaman:

```text
/
/temp-mail
/temporary-email
/10-minute-mail
/disposable-email
/temporary-email-generator
```

Setiap halaman harus memiliki:

- Unique title.
- Meta description.
- H1.
- FAQ.
- Internal linking.
- Structured data jika relevan.
- Mobile responsive.
- Fast loading.

## 17. Landing Page

Struktur:

```text
Header

Temporary Email

[ x82jd91@domain.com ]

[ Copy ]

Expires in 09:42

Inbox

[ Email List ]

How Temporary Email Works

FAQ

Advertisement

Footer
```

## 18. Mobile

Website harus mobile-first.

Target:

- Android Chrome.
- iPhone Safari.
- Desktop Chrome.
- Firefox.
- Edge.

Tombol utama harus mudah disentuh.

## 19. Performance

Target:

- Initial page load < 2 detik.
- API response < 500 ms untuk request normal.
- Inbox update beberapa detik.
- Lazy load email body.
- Compress attachment.
- Cache static assets.
- Gunakan CDN Cloudflare.

## 20. Email Lifecycle

```text
Generated
   ↓
Active
   ↓
Receiving
   ↓
Expired
   ↓
Deleted
```

Cron atau Queue menjalankan cleanup.

Contoh:

```text
Setiap 1 menit
↓
Cari email expired
↓
Delete messages
↓
Delete attachments
↓
Delete temporary address
```

## 21. Logging

Sistem mencatat:

- API request.
- Email received.
- Email rejected.
- Rate limit.
- Abuse detection.
- Worker error.
- Application error.

Jangan menyimpan data pribadi lebih lama dari kebutuhan operasional.

## 22. Teknologi

Backend:

```text
Laravel
PHP 8.3+
MySQL
Redis
```

Frontend:

```text
Blade
Tailwind CSS
JavaScript
```

Infrastructure:

```text
Cloudflare DNS
Cloudflare Email
Cloudflare Workers
Nginx
Ubuntu
```

Optional:

```text
WebSocket
Queue Worker
Object Storage
```

## 23. Struktur Project

```text
app/
├── Http/
├── Models/
├── Services/
│   ├── TemporaryEmailService.php
│   ├── EmailReceiveService.php
│   ├── EmailParserService.php
│   └── ExpirationService.php
├── Jobs/
│   ├── ProcessIncomingEmail.php
│   └── CleanupExpiredEmails.php
└── Console/

resources/
├── views/
│   ├── home.blade.php
│   ├── inbox.blade.php
│   └── email.blade.php
└── js/

routes/
├── web.php
└── api.php

cloudflare/
└── email-worker/
    └── index.js
```

## 24. MVP Development Phase

### Phase 1

```text
Generate Email
Inbox
Receive Email
Email Detail
Copy Email
Expiration
Delete
```

### Phase 2

```text
Cloudflare Worker
Redis
Queue
Attachment
Rate Limit
Security
```

### Phase 3

```text
Admin Panel
Domain Management
Abuse Management
Statistics
```

### Phase 4

```text
SEO
FAQ
Landing Page
Advertising
Analytics
```

## 25. Success Metrics

Target awal:

```text
Daily Visitors
Daily Generated Emails
Email Delivery Success Rate
Average Session Duration
Returning Visitors
Ad RPM
Page Views
```

Fokus:

```text
Traffic
↓
Email usage
↓
Returning users
↓
Page views
↓
Advertising revenue
```

## 26. Acceptance Criteria

MVP dianggap selesai jika:

- User dapat membuat temporary email tanpa login.
- Email dapat menerima pesan dari internet.
- Inbox menampilkan email masuk.
- User dapat membuka isi email.
- User dapat copy alamat email.
- Temporary email otomatis expired.
- Email expired otomatis terhapus.
- Sistem memiliki rate limit.
- Email HTML aman dari XSS.
- Website responsive.
- Cloudflare Worker berhasil menerima email.
- Admin dapat mengelola domain.
- Sistem dapat menampilkan statistik dasar.
- Iklan dapat ditempatkan tanpa mengganggu fungsi utama.

## 27. Prioritas

### P0

- Generate email.
- Receive email.
- Inbox.
- Email detail.
- Expiration.
- Delete.
- Cloudflare Worker.

### P1

- Redis.
- Attachment.
- Rate limit.
- Admin panel.
- Domain management.
- Security.

### P2

- SEO.
- Multi-domain.
- Analytics.
- Advertising optimization.
- PWA.

### P3

- Public API.
- Developer documentation.
- Advanced spam detection.
- Realtime WebSocket.

## 28. Prinsip Produk

Produk harus:

- Gratis.
- Tidak membutuhkan registrasi.
- Cepat.
- Sederhana.
- Mobile friendly.
- Minim data yang disimpan.
- Mudah digunakan.
- Tidak mengganggu pengguna dengan iklan berlebihan.
- Menggunakan Cloudflare untuk layer email dan security.
- Memiliki proteksi abuse yang kuat.

## 29. Development Rule

Setiap task development wajib:

1. Membaca `PRD.md` terlebih dahulu.
2. Memastikan task sesuai dengan requirement PRD.
3. Tidak mengubah requirement utama tanpa persetujuan.
4. Jika menemukan konflik requirement, berhenti dan jelaskan konfliknya.
5. Setelah implementasi, periksa kembali hasil terhadap Acceptance Criteria.
6. Update dokumentasi jika terjadi perubahan arsitektur atau requirement.
