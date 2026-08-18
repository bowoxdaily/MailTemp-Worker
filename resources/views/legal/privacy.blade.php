@php($title = 'Kebijakan Privasi')
@component('legal.layout', ['title' => $title])
    <p><strong>Catatan:</strong> Ini draft umum, bukan nasihat hukum. Sesuaikan dengan entitas, vendor, dan yurisdiksi Anda.
    </p>
    <h2>Data yang diproses</h2>
    <p>EmailTemp memproses alamat sementara, token sesi, metadata pesan, dan data teknis yang diperlukan untuk menjalankan
        inbox, keamanan, logging, dan penghapusan otomatis.</p>
    <h2>Penyimpanan</h2>
    <p>Alamat dan pesan dihapus saat masa aktif berakhir atau saat Anda menghapusnya. Retensi log operasional dibatasi
        sesuai kebutuhan keamanan dan operasional.</p>
    <h2>Berbagi data</h2>
    <p>Data dapat diproses oleh penyedia infrastruktur yang membantu menjalankan layanan. EmailTemp tidak menjual isi pesan
        untuk iklan.</p>
    <h2>Kontak dan hak Anda</h2>
    <p>Untuk pertanyaan privasi atau permintaan terkait data, hubungi <a
            href="mailto:support@example.com">support@example.com</a>. Ganti alamat ini dengan kontak resmi sebelum produksi.
    </p>
@endcomponent
