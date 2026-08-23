@php
    $title = 'Kebijakan Cookie';
    $appName = \App\Models\Setting::get('app_name', 'EmailTemp');
    $contactEmail = \App\Models\Setting::get('contact_email', 'support@example.com');
@endphp
@component('legal.layout', ['title' => $title])
    <h2>1. Apa itu Cookie</h2>
    <p>Cookie adalah file kecil yang disimpan di perangkat Anda saat mengunjungi situs web. {{ $appName }} menggunakan
        cookie dan teknologi penyimpanan lokal browser untuk menyediakan layanan.</p>

    <h2>2. Cookie yang Kami Gunakan</h2>

    <h3>Cookie Penting (Wajib)</h3>
    <p>Cookie ini diperlukan agar layanan berfungsi dengan baik:</p>
    <ul>
        <li><strong>Sesi aplikasi</strong> — mengidentifikasi sesi Anda saat menggunakan layanan</li>
        <li><strong>Token CSRF</strong> — melindungi dari serangan cross-site request forgery</li>
        <li><strong>Penyimpanan lokal inbox</strong> — menyimpan data sesi inbox sementara di perangkat Anda</li>
    </ul>

    <h3>Cookie Pihak Ketiga</h3>
    <p>CDN atau layanan pihak ketiga yang digunakan untuk menyajikan aset statis mungkin memiliki kebijakan cookie
        tersendiri. {{ $appName }} tidak menggunakan cookie pelacakan iklan atau analitik pihak ketiga.</p>

    <h2>3. Penyimpanan Lokal (Local Storage)</h2>
    <p>{{ $appName }} menggunakan local storage browser untuk menyimpan preferensi tampilan (tema gelap/terang) dan
        data
        sesi inbox sementara. Data ini hanya tersimpan di perangkat Anda dan tidak dikirim ke server kami.</p>

    <h2>4. Kontrol Anda</h2>
    <p>Anda dapat mengelola cookie melalui pengaturan browser:</p>
    <ul>
        <li>Menghapus semua cookie dan data situs kapan saja</li>
        <li>Memblokir cookie pihak ketiga</li>
        <li>Mengatur browser untuk memperingatkan sebelum menyimpan cookie</li>
    </ul>
    <p><strong>Catatan:</strong> Menghapus penyimpanan lokal akan menghapus sesi inbox sementara Anda dan data tersebut
        tidak dapat dipulihkan.</p>

    <h2>5. Perubahan Kebijakan</h2>
    <p>Kebijakan cookie ini dapat diperbarui sewaktu-waktu. Perubahan berlaku efektif saat dipublikasikan di halaman ini.
    </p>

    <h2>6. Kontak</h2>
    <p>Untuk pertanyaan tentang kebijakan cookie, hubungi kami di
        <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>.
    </p>
@endcomponent
