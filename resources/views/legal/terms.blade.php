@php
    $title = 'Syarat & Ketentuan';
    $appName = \App\Models\Setting::get('app_name', 'EmailTemp');
    $contactEmail = \App\Models\Setting::get('contact_email', 'support@example.com');
@endphp
@component('legal.layout', ['title' => $title])
    <h2>1. Layanan</h2>
    <p>{{ $appName }} menyediakan alamat email sementara untuk OTP, verifikasi, testing, dan penggunaan sah lainnya.
        Alamat
        dan pesan memiliki masa aktif terbatas dan akan dihapus secara otomatis setelah kedaluwarsa.</p>

    <h2>2. Penggunaan yang Diizinkan</h2>
    <p>Anda diizinkan menggunakan layanan {{ $appName }} untuk keperluan sah seperti verifikasi akun, menerima OTP,
        testing pengiriman email, dan penggunaan pribadi lainnya yang tidak melanggar hukum.</p>

    <h2>3. Penggunaan yang Dilarang</h2>
    <p>Anda dilarang menggunakan layanan untuk:</p>
    <ul>
        <li>Mengirim atau menerima spam, phishing, atau konten berbahaya</li>
        <li>Penipuan, penyalahgunaan akun pihak ketiga, atau aktivitas ilegal</li>
        <li>Distribusi malware atau konten yang melanggar hukum</li>
        <li>Menghindari sistem keamanan atau pembatasan layanan lain</li>
        <li>Penggunaan otomatis massal tanpa izin tertulis</li>
    </ul>

    <h2>4. Data dan Ketersediaan</h2>
    <p>Semua email bersifat sementara dan publik. Jangan gunakan layanan ini untuk menerima password reset, komunikasi
        rahasia, atau informasi sensitif. {{ $appName }} tidak menjamin ketersediaan layanan secara terus-menerus dan
        berhak membatasi atau menghentikan akses demi keamanan.</p>

    <h2>5. Batasan Tanggung Jawab</h2>
    <p>{{ $appName }} disediakan "sebagaimana adanya" tanpa jaminan apa pun. Kami tidak bertanggung jawab atas kerugian
        yang timbul dari penggunaan atau ketidakmampuan menggunakan layanan, termasuk kehilangan data atau gangguan
        layanan.</p>

    <h2>6. Perubahan Ketentuan</h2>
    <p>Kami berhak mengubah syarat dan ketentuan ini kapan saja. Perubahan berlaku efektif saat dipublikasikan di halaman
        ini. Penggunaan berkelanjutan setelah perubahan dianggap sebagai persetujuan.</p>

    <h2>7. Kontak</h2>
    <p>Untuk pertanyaan mengenai syarat dan ketentuan ini, hubungi kami di
        <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.
    </p>
@endcomponent
