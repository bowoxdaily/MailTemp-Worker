@php
    $title = 'Hubungi Kami';
    $appName = \App\Models\Setting::get('app_name', 'EmailTemp');
    $contactEmail = \App\Models\Setting::get('contact_email', 'support@example.com');
@endphp
@component('legal.layout', ['title' => $title])
    <p>Butuh bantuan, ingin melaporkan penyalahgunaan, atau punya pertanyaan privasi? Hubungi tim {{ $appName }}
        melalui
        email di bawah ini.</p>

    <div class="mt-6 rounded-xl bg-white p-6 shadow-sm border border-slate-100">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Email</p>
                <a class="text-indigo-600 font-semibold hover:underline"
                    href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <h2>Hal yang Bisa Kami Bantu</h2>
        <ul>
            <li>Pertanyaan tentang layanan {{ $appName }}</li>
            <li>Melaporkan penyalahgunaan atau konten berbahaya</li>
            <li>Permintaan terkait data dan privasi</li>
            <li>Masukan dan saran perbaikan layanan</li>
            <li>Pertanyaan teknis dan integrasi</li>
        </ul>
    </div>

    <p class="mt-6 text-sm text-slate-500">Kami akan berusaha merespons setiap email dalam waktu 1–3 hari kerja.</p>
@endcomponent
