@php
    $title = 'Kebijakan Privasi';
    $appName = \App\Models\Setting::get('app_name', 'EmailTemp');
    $contactEmail = \App\Models\Setting::get('contact_email', 'support@example.com');
@endphp
@component('legal.layout', ['title' => $title])
    <h2>1. Pendahuluan</h2>
    <p>Kebijakan privasi ini menjelaskan bagaimana {{ $appName }} mengumpulkan, menggunakan, dan melindungi informasi
        Anda saat menggunakan layanan email sementara kami.</p>

    <h2>2. Data yang Dikumpulkan</h2>
    <p>{{ $appName }} memproses data berikut secara otomatis:</p>
    <ul>
        <li><strong>Alamat email sementara</strong> — dibuat secara acak untuk sesi Anda</li>
        <li><strong>Isi pesan email</strong> — email yang diterima di alamat sementara</li>
        <li><strong>Token sesi</strong> — untuk mengidentifikasi kepemilikan inbox</li>
        <li><strong>Data teknis</strong> — alamat IP, user agent, dan timestamp untuk keamanan</li>
    </ul>

    <h2>3. Tujuan Penggunaan Data</h2>
    <p>Data digunakan semata-mata untuk:</p>
    <ul>
        <li>Menyediakan layanan inbox email sementara</li>
        <li>Keamanan dan pencegahan penyalahgunaan</li>
        <li>Logging operasional dan debugging</li>
        <li>Penghapusan otomatis sesuai masa aktif</li>
    </ul>

    <h2>4. Penyimpanan dan Penghapusan</h2>
    <p>Alamat email sementara dan seluruh pesan dihapus secara otomatis saat masa aktif berakhir. Anda juga dapat
        menghapus inbox secara manual kapan saja. Log operasional disimpan untuk jangka waktu terbatas sesuai kebutuhan
        keamanan.</p>

    <h2>5. Berbagi Data</h2>
    <p>{{ $appName }} tidak menjual, menyewakan, atau membagikan isi pesan untuk tujuan iklan atau pemasaran. Data
        dapat
        diproses oleh penyedia infrastruktur (hosting, CDN, email routing) yang diperlukan untuk menjalankan layanan.</p>

    <h2>6. Keamanan</h2>
    <p>Kami menerapkan langkah keamanan teknis yang wajar untuk melindungi data Anda. Namun, mengingat sifat layanan email
        sementara yang terbuka, jangan gunakan {{ $appName }} untuk informasi sensitif atau rahasia.</p>

    <h2>7. Hak Anda</h2>
    <p>Anda berhak untuk:</p>
    <ul>
        <li>Menghapus inbox sementara Anda kapan saja</li>
        <li>Meminta informasi tentang data yang kami proses</li>
        <li>Mengajukan pertanyaan atau keluhan terkait privasi</li>
    </ul>

    <h2>8. Perubahan Kebijakan</h2>
    <p>Kebijakan ini dapat diperbarui sewaktu-waktu. Perubahan berlaku efektif saat dipublikasikan di halaman ini.</p>

    <h2>9. Kontak</h2>
    <p>Untuk pertanyaan privasi atau permintaan terkait data, hubungi kami di
        <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.
    </p>
@endcomponent
