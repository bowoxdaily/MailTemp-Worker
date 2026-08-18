@php($title = 'Kebijakan Cookie')
@component('legal.layout', ['title' => $title])
    <p><strong>Catatan:</strong> Draft ini perlu disesuaikan dengan cookie dan analytics yang benar-benar aktif.</p>
    <h2>Cookie penting</h2>
    <p>EmailTemp dapat memakai penyimpanan lokal browser untuk mempertahankan sesi inbox sementara pada perangkat Anda. Data
        ini tidak menggantikan penghapusan server.</p>
    <h2>Cookie pihak ketiga</h2>
    <p>CDN atau layanan pihak ketiga dapat memiliki kebijakan cookie sendiri. Periksa konfigurasi produksi dan
        dokumentasikan vendor yang digunakan.</p>
    <h2>Kontrol Anda</h2>
    <p>Anda dapat menghapus data situs melalui pengaturan browser. Menghapus penyimpanan lokal dapat membuat sesi inbox
        tidak dapat dipulihkan.</p>
@endcomponent
