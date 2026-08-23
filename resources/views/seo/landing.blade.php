<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $appName = \App\Models\Setting::get('app_name', 'EmailTemp');
        $logoUrl = \App\Models\Setting::get('app_logo_url');
        $logoHeight = (int) \App\Models\Setting::get('app_logo_height', 32);
        $faviconUrl = \App\Models\Setting::get('app_favicon_url') ?: asset('favicon.ico');
    @endphp
    <title>{{ $page['title'] }} | {{ $appName }}</title>
    <meta name="description" content="{{ $page['description'] }}">
    <meta name="keywords"
        content="{{ strtolower($page['name']) }}, temporary email, temp mail, disposable email, generator email, otp email">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="{{ $appName }}">
    <meta name="theme-color" content="#101820">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" href="{{ $faviconUrl }}">
    @if ($logoUrl)
        <link rel="apple-touch-icon" href="{{ $logoUrl }}">
    @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $page['title'] }}">
    <meta property="og:description" content="{{ $page['description'] }}">
    <meta property="og:url" content="{{ url()->current() }}">
    @if ($logoUrl)
        <meta property="og:image" content="{{ $logoUrl }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $page['title'] }}">
    <meta name="twitter:description" content="{{ $page['description'] }}">
    @if ($logoUrl)
        <meta name="twitter:image" content="{{ $logoUrl }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#f3f0e9] text-[#101820]">
    <header class="bg-[#101820] text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-5 py-4 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 font-bold tracking-tight">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $appName }}"
                        style="height: {{ $logoHeight }}px; width: auto; max-height: 48px;">
                @endif
                <span class="text-lg font-bold">{{ $appName }}</span>
            </a>
            <a href="{{ route('home') }}"
                class="rounded-lg bg-[#f3b23c] px-4 py-2 text-sm font-bold text-[#101820]">Buat email gratis</a>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-5 py-12 sm:py-20">
        <nav aria-label="Breadcrumb" class="text-sm text-slate-500">
            <a href="{{ route('home') }}" class="hover:text-[#a56b0b]">{{ $appName }}</a>
            <span class="px-2">/</span>{{ $page['name'] }}
        </nav>
        <section class="mt-6 rounded-3xl bg-[#101820] px-6 py-12 text-white sm:px-12">
            <p class="text-xs font-bold uppercase tracking-[.2em] text-[#f3b23c]">{{ strtoupper($appName) }} /
                {{ strtoupper($page['name']) }}</p>
            <h1 class="mt-4 max-w-3xl text-4xl font-bold tracking-tight sm:text-6xl">{{ $page['intro'] }}</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300">{{ $page['copy'] }}</p>
            <a href="{{ route('home') }}"
                class="mt-8 inline-flex rounded-xl bg-[#f3b23c] px-6 py-3.5 font-bold text-[#101820] hover:bg-amber-300">Generate
                temporary email</a>
        </section>

        <section class="mt-12 grid gap-5 sm:grid-cols-3">
            @foreach ([['title' => 'Tanpa akun', 'copy' => 'Mulai tanpa login atau registrasi.'], ['title' => 'Baca inbox', 'copy' => 'Terima OTP dan email verifikasi dengan cepat.'], ['title' => 'Auto-delete', 'copy' => 'Alamat dan pesan dihapus saat kedaluwarsa.']] as $benefit)
                <article class="rounded-2xl border border-slate-200 bg-white p-6">
                    <h2 class="font-bold">{{ $benefit['title'] }}</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $benefit['copy'] }}</p>
                </article>
            @endforeach
        </section>

        <section class="mt-14 max-w-3xl">
            <h2 class="text-2xl font-bold">Email sementara untuk kebutuhan nyata</h2>
            <p class="mt-4 leading-8 text-slate-600">{{ $appName }} cocok untuk menguji signup dan notifikasi,
                menerima kode
                verifikasi, atau menjaga email utama dari spam. Pilih durasi 10 menit, 30 menit, atau 1 jam, lalu
                gunakan inbox selama diperlukan.</p>
        </section>

        <section class="mt-14 max-w-3xl">
            <h2 class="text-2xl font-bold">Pertanyaan umum</h2>
            <div class="mt-5 space-y-4">
                <details class="rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="cursor-pointer font-bold">Apakah {{ strtolower($page['name']) }} perlu registrasi?
                    </summary>
                    <p class="mt-3 leading-7 text-slate-600">Tidak. {{ $appName }} membuat alamat sementara tanpa
                        akun dan
                        tanpa password.</p>
                </details>
                <details class="rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="cursor-pointer font-bold">Berapa lama email bisa digunakan?</summary>
                    <p class="mt-3 leading-7 text-slate-600">Durasi tersedia 10 menit, 30 menit, dan 1 jam. Alamat
                        serta
                        pesan dihapus otomatis setelah masa aktif berakhir.</p>
                </details>
                <details class="rounded-xl border border-slate-200 bg-white p-5">
                    <summary class="cursor-pointer font-bold">Untuk apa temporary email digunakan?</summary>
                    <p class="mt-3 leading-7 text-slate-600">Untuk OTP, verifikasi, testing email, dan situasi saat
                        Anda
                        tidak ingin memberikan alamat email utama.</p>
                </details>
            </div>
        </section>

        <section class="mt-14 border-t border-slate-200 pt-8">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-500">Jelajahi {{ $appName }}</h2>
            <div class="mt-4 flex flex-wrap gap-x-5 gap-y-3 text-sm font-semibold text-[#a56b0b]">
                @foreach (['temp-mail', 'temporary-email', '10-minute-mail', 'disposable-email', 'temporary-email-generator'] as $relatedSlug)
                    @if ($relatedSlug !== request()->segment(1))
                        <a href="{{ route("seo.{$relatedSlug}") }}"
                            class="hover:text-[#101820]">{{ $seoPages[$relatedSlug]['name'] ?? ucwords(str_replace('-', ' ', $relatedSlug)) }}</a>
                    @endif
                @endforeach
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 py-8 text-center text-sm text-slate-500">
        {{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' ' . $appName . '. Semua email dihapus otomatis setelah masa aktif berakhir.') }}
    </footer>

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebPage',
                    '@id' => url()->current() . '#webpage',
                    'url' => url()->current(),
                    'name' => $page['title'],
                    'description' => $page['description'],
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        '@id' => url('/#website'),
                        'name' => $appName,
                        'url' => url('/'),
                    ],
                    'inLanguage' => 'id-ID',
                ],
                [
                    '@type' => 'BreadcrumbList',
                    '@id' => url()->current() . '#breadcrumb',
                    'itemListElement' => [
                        [
                            '@type' => 'ListItem',
                            'position' => 1,
                            'name' => 'Home',
                            'item' => url('/'),
                        ],
                        [
                            '@type' => 'ListItem',
                            'position' => 2,
                            'name' => $page['name'],
                            'item' => url()->current(),
                        ],
                    ],
                ],
                [
                    '@type' => 'FAQPage',
                    '@id' => url()->current() . '#faq',
                    'mainEntity' => [
                        [
                            '@type' => 'Question',
                            'name' => "Apakah {$page['name']} perlu registrasi?",
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Tidak. ' . $appName . ' membuat alamat sementara tanpa akun dan tanpa password.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Berapa lama email bisa digunakan?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Durasi tersedia 10 menit, 30 menit, dan 1 jam. Alamat serta pesan dihapus otomatis setelah masa aktif berakhir.',
                            ],
                        ],
                        [
                            '@type' => 'Question',
                            'name' => 'Untuk apa temporary email digunakan?',
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => 'Untuk OTP, verifikasi, testing email, dan situasi saat Anda tidak ingin memberikan alamat email utama.',
                            ],
                        ],
                    ],
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</body>

</html>
