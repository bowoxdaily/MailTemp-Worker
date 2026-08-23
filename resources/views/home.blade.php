<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="dark && 'dark'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $appName = \App\Models\Setting::get('app_name', 'EmailTemp');
        $metaTitle = \App\Models\Setting::get('meta_title') ?: "{$appName} — Email Sementara Gratis Tanpa Registrasi";
        $metaDescription =
            \App\Models\Setting::get('meta_description') ?:
            'Email sementara gratis, aman, dan instan untuk verifikasi OTP, testing aplikasi, dan melindungi inbox pribadi tanpa registrasi.';
        $metaKeywords =
            \App\Models\Setting::get('meta_keywords') ?:
            'temp mail, temporary email, email sementara, 10 minute mail, disposable email, generator email, otp email, fake mail';
        $logoUrl = \App\Models\Setting::get('app_logo_url');
        $faviconUrl = \App\Models\Setting::get('app_favicon_url') ?: asset('favicon.ico');
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ $metaKeywords }}">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <meta name="author" content="{{ $appName }}">
    <meta name="theme-color" content="#00a8e8" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <link rel="canonical" href="{{ url('/') }}">
    <link rel="icon" href="{{ $faviconUrl }}">
    @if ($logoUrl)
        <link rel="apple-touch-icon" href="{{ $logoUrl }}">
    @endif

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $appName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ url('/') }}">
    @if ($logoUrl)
        <meta property="og:image" content="{{ $logoUrl }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if ($logoUrl)
        <meta name="twitter:image" content="{{ $logoUrl }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700&display=swap"
            rel="stylesheet">
    </noscript>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace']
                    },
                    colors: {
                        brand: {
                            50: '#eef9ff',
                            100: '#d9f1ff',
                            200: '#bce8ff',
                            300: '#8edaff',
                            400: '#59c3ff',
                            500: '#00a8e8',
                            600: '#0088cc',
                            700: '#006da5',
                            800: '#005c88',
                            900: '#064d70',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            color-scheme: light dark;
        }

        [x-cloak] {
            display: none !important
        }

        ::selection {
            background: #00a8e8;
            color: white;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #fafbfc;
        }

        .dark body {
            background: #0b1120;
        }

        button,
        select {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible,
        select:focus-visible {
            outline: 2px solid #00a8e8;
            outline-offset: 2px;
        }

        /* Hero gradient mesh */
        .hero-bg {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(0, 168, 232, 0.15), transparent),
                radial-gradient(ellipse 60% 40% at 80% 50%, rgba(99, 102, 241, 0.08), transparent),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0, 168, 232, 0.06), transparent);
        }

        .dark .hero-bg {
            background:
                radial-gradient(ellipse 80% 50% at 50% -20%, rgba(0, 168, 232, 0.12), transparent),
                radial-gradient(ellipse 60% 40% at 80% 50%, rgba(99, 102, 241, 0.08), transparent),
                radial-gradient(ellipse 60% 40% at 20% 80%, rgba(0, 168, 232, 0.05), transparent);
        }

        /* Grid pattern overlay */
        .grid-pattern {
            background-image:
                linear-gradient(rgba(0, 0, 0, .03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 0, 0, .03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        .dark .grid-pattern {
            background-image:
                linear-gradient(rgba(255, 255, 255, .02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .02) 1px, transparent 1px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #00a8e8 0%, #6366f1 50%, #00a8e8 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {

            0%,
            100% {
                background-position: 0% center;
            }

            50% {
                background-position: 200% center;
            }
        }

        /* Floating animation */
        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .float-slow {
            animation: float 6s ease-in-out infinite;
        }

        .float-mid {
            animation: float 4s ease-in-out infinite 1s;
        }

        .float-fast {
            animation: float 5s ease-in-out infinite 2s;
        }

        /* Step connector */
        .step-connector {
            background: linear-gradient(90deg, #00a8e8, #6366f1);
        }

        /* Glass card */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .dark .glass-card {
            background: rgba(15, 23, 42, 0.6);
        }

        .email-frame {
            min-height: 320px;
            width: 100%;
            border: 0;
            background: white;
        }

        .dark .email-frame {
            background: #1e293b;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'WebApplication',
                    '@id' => url('/#webapp'),
                    'name' => $appName,
                    'url' => url('/'),
                    'applicationCategory' => 'UtilitiesApplication',
                    'operatingSystem' => 'Web Browser',
                    'description' => $metaDescription,
                    'offers' => [
                        '@type' => 'Offer',
                        'price' => '0',
                        'priceCurrency' => 'USD',
                    ],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => url('/#website'),
                    'url' => url('/'),
                    'name' => $appName,
                    'description' => $metaDescription,
                    'inLanguage' => 'id-ID',
                ],
                [
                    '@type' => 'Organization',
                    '@id' => url('/#organization'),
                    'name' => $appName,
                    'url' => url('/'),
                    'logo' => $logoUrl ?: url('/favicon.ico'),
                ],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>

<body class="h-full font-sans text-slate-900 dark:text-slate-100 antialiased">

    <script>
        window.domains = @json($domains ?? []);
    </script>

    <div x-data="emailApp()" x-cloak class="min-h-full">

        {{-- ═══════════════════════════════════════════════════════════════
            HEADER
        ═══════════════════════════════════════════════════════════════ --}}
        <header
            class="sticky top-0 z-50 border-b border-slate-200/60 dark:border-slate-800/60 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl transition-all">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 group"
                    aria-label="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }} home">
                    @if (\App\Models\Setting::get('app_logo_url'))
                        <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                            alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}"
                            style="height: {{ (int) \App\Models\Setting::get('app_logo_height', 32) }}px; width: auto; max-height: 100px;">
                    @else
                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-brand-500 to-indigo-500 shadow-lg shadow-brand-500/20 group-hover:shadow-brand-500/40 transition-shadow">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span
                            class="text-lg font-extrabold tracking-tight text-slate-900 dark:text-white">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</span>
                    @endif
                </a>
                <div class="flex items-center gap-2">
                    <nav class="hidden items-center sm:flex">
                        <a href="#fitur"
                            class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 dark:hover:text-brand-400 px-3 py-2 rounded-lg transition-colors">Fitur</a>
                        <a href="#cara-kerja"
                            class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 dark:hover:text-brand-400 px-3 py-2 rounded-lg transition-colors">Cara
                            Kerja</a>
                        <a href="#faq"
                            class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 dark:hover:text-brand-400 px-3 py-2 rounded-lg transition-colors">FAQ</a>
                    </nav>
                    <div class="w-px h-5 bg-slate-200 dark:bg-slate-700 mx-2 hidden sm:block"></div>
                    <button type="button" @click="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light')"
                        class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
                        :title="dark ? 'Mode terang' : 'Mode gelap'"
                        :aria-label="dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                    <a href="{{ route('home') }}"
                        class="hidden sm:inline-flex items-center gap-1.5 rounded-lg bg-brand-500 hover:bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-brand-500/20 hover:shadow-brand-500/30 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Baru
                    </a>
                </div>
            </div>
            @if ($adHeader = \App\Models\Setting::get('ad_header'))
                <div class="mx-auto max-w-6xl px-5 py-4 text-center" aria-label="Advertisement">
                    {!! $adHeader !!}
                </div>
            @endif
        </header>

        {{-- ═══════════════════════════════════════════════════════════════
            MAIN CONTENT
        ═══════════════════════════════════════════════════════════════ --}}
        <main>

            {{-- ── HERO + GENERATE ── --}}
            <section class="hero-bg grid-pattern relative overflow-hidden">
                {{-- Floating decorative elements --}}
                <div class="absolute inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
                    <div
                        class="float-slow absolute top-20 left-[10%] w-16 h-16 rounded-2xl bg-brand-400/10 dark:bg-brand-400/5 rotate-12">
                    </div>
                    <div
                        class="float-mid absolute top-32 right-[15%] w-12 h-12 rounded-xl bg-indigo-400/10 dark:bg-indigo-400/5 -rotate-6">
                    </div>
                    <div
                        class="float-fast absolute bottom-20 left-[20%] w-10 h-10 rounded-lg bg-brand-400/8 dark:bg-brand-400/3 rotate-45">
                    </div>
                    <div
                        class="float-slow absolute bottom-32 right-[25%] w-14 h-14 rounded-2xl bg-indigo-400/8 dark:bg-indigo-400/3 -rotate-12">
                    </div>
                </div>

                <div class="relative mx-auto max-w-6xl px-5 lg:px-8 pt-16 pb-20 sm:pt-24 sm:pb-28">

                    {{-- Hero text (only when no email yet) --}}
                    <template x-if="!email">
                        <div>
                            <div class="mx-auto max-w-3xl text-center mb-12">
                                {{-- Trust badges --}}
                                <div
                                    class="inline-flex items-center gap-2 rounded-full bg-brand-50 dark:bg-brand-900/20 border border-brand-100 dark:border-brand-800/30 px-4 py-1.5 mb-6">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-xs font-semibold text-brand-700 dark:text-brand-300">Gratis •
                                        Tanpa registrasi • Auto-delete</span>
                                </div>

                                <h1
                                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1]">
                                    <span class="text-slate-900 dark:text-white">Email sementara</span><br>
                                    <span class="gradient-text">aman & instan</span>
                                </h1>
                                <p
                                    class="mx-auto mt-5 max-w-xl text-lg text-slate-500 dark:text-slate-400 leading-relaxed">
                                    Buat alamat email sekali pakai dalam hitungan detik. Terima OTP, verifikasi akun,
                                    dan lindungi privasi Anda.
                                </p>
                            </div>

                            {{-- Generate Card --}}
                            <div class="mx-auto max-w-2xl">
                                <div
                                    class="glass-card rounded-2xl border border-slate-200/80 dark:border-slate-700/50 p-6 sm:p-8 shadow-xl shadow-slate-200/50 dark:shadow-none">

                                    {{-- Email input row --}}
                                    <div
                                        class="flex flex-col sm:flex-row items-stretch rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-1 focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 dark:focus-within:ring-brand-900/30 transition-all gap-0">
                                        <div class="relative flex-1 flex items-center">
                                            <input type="text" x-model="customUsername" placeholder="username"
                                                class="w-full bg-transparent border-0 pl-4 pr-10 py-3 text-base font-mono font-semibold text-slate-800 dark:text-slate-200 placeholder-slate-300 dark:placeholder-slate-600 focus:outline-none focus:ring-0 sm:text-right"
                                                aria-label="Username kustom">
                                            <button @click="randomizeUsername()"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 hover:text-brand-500 dark:text-slate-600 dark:hover:text-brand-400 transition-colors"
                                                title="Acak username" aria-label="Acak username">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div
                                            class="hidden sm:flex items-center justify-center text-slate-300 dark:text-slate-600 font-mono text-lg px-1 select-none">
                                            @</div>
                                        <div
                                            class="flex-1 sm:max-w-[220px] flex items-center border-t sm:border-t-0 sm:border-l border-slate-200 dark:border-slate-700">
                                            <span
                                                class="sm:hidden text-slate-300 dark:text-slate-600 font-mono text-lg pl-4 pr-1 select-none">@</span>
                                            <select x-model="customDomain"
                                                class="w-full bg-transparent border-0 px-3 py-3 text-base font-mono font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:ring-0 cursor-pointer"
                                                aria-label="Domain">
                                                <template x-for="dom in domains" :key="dom">
                                                    <option :value="dom" x-text="dom"></option>
                                                </template>
                                                <option x-show="domains.length === 0" value="">(No domains)
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Expiry pills --}}
                                    <div class="mt-5 flex flex-col sm:flex-row items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-xs font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Aktif</span>
                                            <div
                                                class="inline-flex items-center gap-0.5 bg-slate-100 dark:bg-slate-800 p-0.5 rounded-lg">
                                                <template x-for="opt in [{v:10,l:'10m'},{v:30,l:'30m'},{v:60,l:'1h'}]"
                                                    :key="opt.v">
                                                    <button type="button" @click="expiryMinutes = opt.v"
                                                        :class="expiryMinutes == opt.v ?
                                                            'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' :
                                                            'text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300'"
                                                        class="px-3 py-1.5 rounded-md text-xs font-semibold transition-all"
                                                        x-text="opt.l">
                                                    </button>
                                                </template>
                                            </div>
                                        </div>

                                        <button @click="generate()"
                                            :disabled="loading || !customUsername || !customDomain"
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-500 to-brand-600 hover:from-brand-600 hover:to-brand-700 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-px active:translate-y-0 transition-all disabled:opacity-50 disabled:pointer-events-none">
                                            <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4" />
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                                            </svg>
                                            <span x-text="loading ? 'Membuat...' : 'Buat Email'"></span>
                                            <svg x-show="!loading" class="w-4 h-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                            </svg>
                                        </button>
                                    </div>

                                    <p x-show="error" x-text="error" role="alert"
                                        class="mt-3 text-sm text-red-500 dark:text-red-400 font-medium text-center">
                                    </p>
                                </div>
                            </div>
                            @if ($adGenerator = \App\Models\Setting::get('ad_generator'))
                                <div class="my-6 text-center" aria-label="Advertisement">
                                    {!! $adGenerator !!}
                                </div>
                            @endif
                        </div>
                    </template>

                    {{-- ── INBOX (when email generated) ── --}}
                    <template x-if="email">
                        <div class="mx-auto max-w-3xl">
                            {{-- Email Address Card --}}
                            <div
                                class="glass-card rounded-2xl border border-slate-200/80 dark:border-slate-700/50 shadow-xl shadow-slate-200/50 dark:shadow-none p-5 sm:p-6 mb-5">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[.15em] mb-1.5">
                                            Alamat Email Anda</p>
                                        <div class="flex items-center gap-2">
                                            <p class="text-lg sm:text-xl font-mono font-bold text-slate-800 dark:text-slate-100 truncate select-all"
                                                x-text="email"></p>
                                            <button @click="copyEmail()"
                                                class="flex-shrink-0 p-1.5 rounded-lg hover:bg-brand-50 dark:hover:bg-brand-900/20 text-slate-400 hover:text-brand-500 transition-all"
                                                title="Salin" aria-label="Salin alamat email">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                            <button @click="showQr = true"
                                                class="flex-shrink-0 p-1.5 rounded-lg hover:bg-brand-50 dark:hover:bg-brand-900/20 text-slate-400 hover:text-brand-500 transition-all"
                                                title="Tampilkan QR Code" aria-label="Tampilkan QR Code">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                </svg>
                                            </button>
                                        </div>
                                        <p x-show="copied" x-transition
                                            class="mt-1 text-xs text-emerald-500 font-semibold">Disalin!</p>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold tabular-nums"
                                            :class="timeLeft < 120 ? 'bg-red-50 dark:bg-red-900/20 text-red-500' :
                                                'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300'">
                                            <span class="h-1.5 w-1.5 rounded-full animate-pulse"
                                                :class="timeLeft < 120 ? 'bg-red-500' : 'bg-emerald-500'"></span>
                                            <span x-text="formatTimeLeft()"></span>
                                        </span>
                                        <button @click="refresh()" :disabled="refreshing"
                                            class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-400 hover:text-brand-500 transition-all disabled:opacity-50"
                                            title="Refresh" aria-label="Muat ulang kotak masuk">
                                            <svg class="w-4 h-4" :class="refreshing && 'animate-spin'" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                        <button @click="deleteEmail()"
                                            class="p-2 rounded-lg border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400 hover:text-red-500 transition-all"
                                            title="Hapus" aria-label="Hapus alamat email">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Messages --}}
                            <div
                                class="glass-card rounded-2xl border border-slate-200/80 dark:border-slate-700/50 shadow-xl shadow-slate-200/50 dark:shadow-none overflow-hidden">
                                <div
                                    class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                    <h2
                                        class="text-sm font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                                        Kotak Masuk</h2>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-brand-50 dark:bg-brand-900/20 text-brand-600 dark:text-brand-400"
                                        x-text="messages.length + ' pesan'"></span>
                                </div>

                                {{-- Empty --}}
                                <template x-if="messages.length === 0">
                                    <div class="py-16 text-center">
                                        <div
                                            class="mx-auto w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-500 dark:text-slate-400 font-semibold text-sm">Belum ada
                                            pesan</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Menunggu email
                                            masuk...</p>
                                    </div>
                                </template>

                                {{-- Message List --}}
                                <template x-if="messages.length > 0 && !selectedMessage">
                                    <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                        <template x-for="msg in messages" :key="msg.id">
                                            <button @click="openMessage(msg.id)"
                                                class="w-full text-left px-5 py-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors flex items-start gap-3">
                                                <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0"
                                                    :class="msg.is_read ? 'bg-slate-200 dark:bg-slate-700' : 'bg-brand-500'">
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex items-baseline justify-between gap-2">
                                                        <p class="text-sm font-semibold truncate"
                                                            :class="msg.is_read ? 'text-slate-600 dark:text-slate-400' :
                                                                'text-slate-900 dark:text-white'"
                                                            x-text="msg.from_name || msg.from_address"></p>
                                                        <span
                                                            class="text-[11px] text-slate-400 dark:text-slate-500 flex-shrink-0 tabular-nums"
                                                            x-text="formatDate(msg.received_at)"></span>
                                                    </div>
                                                    <p class="text-sm text-slate-400 dark:text-slate-500 truncate mt-0.5"
                                                        x-text="msg.subject || '(No Subject)'"></p>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                </template>

                                {{-- Message Detail --}}
                                <template x-if="selectedMessage">
                                    <div>
                                        <div
                                            class="px-5 py-3 border-b border-slate-100 dark:border-slate-800 flex items-center gap-3">
                                            <button @click="selectedMessage = null"
                                                class="p-1 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                                                aria-label="Kembali">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </button>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate"
                                                    x-text="selectedMessage.subject || '(No Subject)'"></p>
                                                <p class="text-xs text-slate-400 dark:text-slate-500"
                                                    x-text="selectedMessage.from_name ? selectedMessage.from_name + ' <' + selectedMessage.from_address + '>' : selectedMessage.from_address">
                                                </p>
                                            </div>
                                            <button @click="deleteMessage(selectedMessage.id)"
                                                class="p-1 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-400 hover:text-red-500 transition-colors"
                                                title="Hapus" aria-label="Hapus pesan ini">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                        <template
                                            x-if="selectedMessage.attachments && selectedMessage.attachments.length > 0">
                                            <div
                                                class="px-5 py-2.5 border-b border-slate-100 dark:border-slate-800 flex flex-wrap gap-2">
                                                <template x-for="att in selectedMessage.attachments"
                                                    :key="att.id">
                                                    <a :href="`/api/v1/inbox/${token}/messages/${selectedMessage.id}/attachments/${att.id}`"
                                                        download
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 hover:bg-brand-50 dark:bg-slate-800 dark:hover:bg-brand-900/30 text-xs font-medium text-slate-700 hover:text-brand-600 dark:text-slate-300 dark:hover:text-brand-400 transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                        </svg>
                                                        <span x-text="att.filename"></span>
                                                        <span class="text-slate-400"
                                                            x-text="formatBytes(att.size_bytes)"></span>
                                                        <svg class="w-3 h-3 text-slate-400" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                        </svg>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                        <div class="px-5 py-5">
                                            <template x-if="selectedMessage.body_html">
                                                <iframe class="email-frame rounded-lg" title="Email content"
                                                    sandbox="allow-same-origin"
                                                    :srcdoc="selectedMessage.body_html"></iframe>
                                            </template>
                                            <template x-if="!selectedMessage.body_html && selectedMessage.body_text">
                                                <pre class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-wrap font-sans"
                                                    x-text="selectedMessage.body_text"></pre>
                                            </template>
                                            <template x-if="!selectedMessage.body_html && !selectedMessage.body_text">
                                                <p class="text-sm text-slate-400 italic">Tidak ada konten.</p>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            @if ($adInbox = \App\Models\Setting::get('ad_inbox'))
                                <div class="my-6 text-center" aria-label="Advertisement">
                                    {!! $adInbox !!}
                                </div>
                            @endif
                        </div>
                    </template>
                </div>
            </section>

            {{-- ── FEATURES SECTION ── --}}
            <section id="fitur"
                class="py-20 bg-white dark:bg-slate-900/50 border-y border-slate-100 dark:border-slate-800/50">
                <div class="mx-auto max-w-6xl px-5 lg:px-8">
                    <div class="text-center mb-14">
                        <span
                            class="inline-block text-xs font-bold text-brand-500 uppercase tracking-[.2em] mb-3">Fitur
                            Unggulan</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            Kenapa memilih {{ \App\Models\Setting::get('app_name', 'EmailTemp') }}?</h2>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {{-- Feature 1 --}}
                        <div
                            class="group p-6 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-brand-200 dark:hover:border-brand-800/50 hover:shadow-lg hover:shadow-brand-500/5 transition-all">
                            <div
                                class="w-12 h-12 rounded-xl bg-brand-50 dark:bg-brand-900/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Tanpa Registrasi</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Tidak perlu akun,
                                password, atau data pribadi. Langsung pakai dalam satu klik.</p>
                        </div>
                        {{-- Feature 2 --}}
                        <div
                            class="group p-6 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-brand-200 dark:hover:border-brand-800/50 hover:shadow-lg hover:shadow-brand-500/5 transition-all">
                            <div
                                class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Auto-Delete</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Email dan inbox
                                dihapus otomatis setelah waktu yang dipilih. Privasi terjaga.</p>
                        </div>
                        {{-- Feature 3 --}}
                        <div
                            class="group p-6 rounded-2xl border border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/30 hover:border-brand-200 dark:hover:border-brand-800/50 hover:shadow-lg hover:shadow-brand-500/5 transition-all sm:col-span-2 lg:col-span-1">
                            <div
                                class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Multi Domain</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">Pilih dari beberapa
                                domain yang tersedia. Gunakan username kustom sesuai kebutuhan.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── CARA KERJA (STEPPER) ── --}}
            <section id="cara-kerja" class="py-20">
                <div class="mx-auto max-w-6xl px-5 lg:px-8">
                    <div class="text-center mb-14">
                        <span
                            class="inline-block text-xs font-bold text-brand-500 uppercase tracking-[.2em] mb-3">Langkah
                            Mudah</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            Cara Kerja</h2>
                    </div>
                    <div class="grid md:grid-cols-3 gap-8 relative">
                        {{-- Connector line (desktop) --}}
                        <div
                            class="hidden md:block absolute top-10 left-[calc(16.67%+24px)] right-[calc(16.67%+24px)] h-0.5 step-connector opacity-20 rounded-full">
                        </div>

                        {{-- Step 1 --}}
                        <div class="text-center">
                            <div
                                class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-brand-50 dark:bg-brand-900/20 border-2 border-brand-100 dark:border-brand-800/30 mb-5">
                                <span class="text-2xl font-black text-brand-500">1</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Buat</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">Pilih username dan
                                domain, klik tombol, dan alamat email siap digunakan.</p>
                        </div>
                        {{-- Step 2 --}}
                        <div class="text-center">
                            <div
                                class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border-2 border-emerald-100 dark:border-emerald-800/30 mb-5">
                                <span class="text-2xl font-black text-emerald-500">2</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Terima</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">Biarkan halaman
                                terbuka. Email masuk otomatis muncul di inbox Anda.</p>
                        </div>
                        {{-- Step 3 --}}
                        <div class="text-center">
                            <div
                                class="relative inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 border-2 border-indigo-100 dark:border-indigo-800/30 mb-5">
                                <span class="text-2xl font-black text-indigo-500">3</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Hilang</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">Alamat dan semua
                                pesan dihapus otomatis setelah timer selesai.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ── FAQ ── --}}
            <section id="faq"
                class="py-20 bg-white dark:bg-slate-900/50 border-y border-slate-100 dark:border-slate-800/50">
                <div class="mx-auto max-w-3xl px-5 lg:px-8">
                    <div class="text-center mb-14">
                        <span
                            class="inline-block text-xs font-bold text-brand-500 uppercase tracking-[.2em] mb-3">FAQ</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">
                            Pertanyaan Umum</h2>
                    </div>
                    <div class="space-y-3" x-data="{ openFaq: null }">
                        <template
                            x-for="(faq, idx) in [
                            { q: 'Apakah harus mendaftar?', a: 'Tidak. {{ \App\Models\Setting::get('app_name', 'EmailTemp') }} membuat sesi sementara tanpa login atau registrasi.' },
                            { q: 'Berapa lama alamat email aktif?', a: 'Pilih 10 menit, 30 menit, atau 1 jam. Default-nya 10 menit.' },
                            { q: 'Apakah pesan disimpan?', a: 'Pesan bersifat sementara dan dihapus bersama alamat setelah masa aktif berakhir.' },
                            { q: 'Apakah bisa kirim email?', a: 'Tidak. Layanan ini hanya untuk menerima email, bukan mengirim.' },
                            { q: 'Apakah aman untuk OTP?', a: 'Ya, untuk verifikasi cepat dan OTP. Jangan gunakan untuk akun penting yang permanen.' }
                        ]"
                            :key="idx">
                            <div class="border border-slate-200 dark:border-slate-700/50 rounded-xl overflow-hidden transition-all"
                                :class="openFaq === idx && 'border-brand-200 dark:border-brand-800/30'">
                                <button @click="openFaq = openFaq === idx ? null : idx"
                                    class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200"
                                        x-text="faq.q"></span>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform flex-shrink-0 ml-3"
                                        :class="openFaq === idx && 'rotate-180'" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="openFaq === idx" x-collapse>
                                    <p class="px-5 pb-4 text-sm text-slate-500 dark:text-slate-400 leading-relaxed"
                                        x-text="faq.a"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </section>

            {{-- ── CTA BANNER ── --}}
            <section class="py-20">
                <div class="mx-auto max-w-4xl px-5 lg:px-8">
                    <div
                        class="relative rounded-3xl bg-gradient-to-br from-brand-500 to-indigo-600 p-10 sm:p-14 text-center overflow-hidden">
                        <div class="absolute inset-0 grid-pattern opacity-10"></div>
                        <div class="relative">
                            <h2 class="text-2xl sm:text-3xl font-extrabold text-white mb-3">Siap mencoba?</h2>
                            <p class="text-brand-100 mb-8 max-w-md mx-auto">Buat email sementara gratis sekarang. Tanpa
                                registrasi, tanpa iklan, tanpa kompromi.</p>
                            <a href="#" @click.prevent="window.scrollTo({ top: 0, behavior: 'smooth' })"
                                class="inline-flex items-center gap-2 rounded-xl bg-white hover:bg-brand-50 px-8 py-3.5 text-sm font-bold text-brand-600 shadow-lg shadow-brand-900/20 hover:-translate-y-px active:translate-y-0 transition-all">
                                Buat Email Sekarang
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </section>

        </main>

        {{-- ═══════════════════════════════════════════════════════════════
            FOOTER
        ═══════════════════════════════════════════════════════════════ --}}
        <footer class="border-t border-slate-100 dark:border-slate-800/50 bg-slate-50 dark:bg-slate-950 py-12">
            <div class="mx-auto max-w-6xl px-5 lg:px-8">
                @if ($adFooter = \App\Models\Setting::get('ad_footer'))
                    <div class="mb-8 text-center" aria-label="Advertisement">
                        {!! $adFooter !!}
                    </div>
                @endif
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 mb-10">
                    <div class="flex items-center gap-2.5">
                        @if (\App\Models\Setting::get('app_logo_url'))
                            <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                                alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}"
                                style="height: {{ (int) \App\Models\Setting::get('app_logo_height', 32) }}px; width: auto; max-height: 100px;">
                        @else
                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-brand-500 to-indigo-500">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span
                                class="text-base font-bold tracking-tight text-slate-800 dark:text-slate-100">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</span>
                        @endif
                    </div>
                    <nav class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm">
                        <a href="#fitur"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">Fitur</a>
                        <a href="#cara-kerja"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">Cara
                            Kerja</a>
                        <a href="#faq"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">FAQ</a>
                        <a href="{{ route('legal.terms') }}"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">Syarat
                            & Ketentuan</a>
                        <a href="{{ route('legal.privacy') }}"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">Privasi</a>
                        <a href="{{ route('legal.cookies') }}"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">Cookie</a>
                        <a href="{{ route('legal.contact') }}"
                            class="font-medium text-slate-500 dark:text-slate-400 hover:text-brand-500 transition-colors">Kontak</a>
                    </nav>
                </div>
                <div
                    class="pt-6 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400 dark:text-slate-500">
                    <p>{{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' EmailTemp. Semua email dihapus otomatis setelah masa aktif berakhir.') }}
                    </p>
                    <div class="flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        <span class="font-medium">Semua sistem operasional</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => [
                ['@type' => 'Question', 'name' => 'Apakah harus mendaftar?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Tidak. ' . \App\Models\Setting::get('app_name', 'EmailTemp') . ' membuat sesi sementara tanpa login atau registrasi.']],
                ['@type' => 'Question', 'name' => 'Berapa lama alamat email aktif?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Pilih 10 menit, 30 menit, atau 1 jam. Default-nya 10 menit.']],
                ['@type' => 'Question', 'name' => 'Apakah pesan disimpan?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Pesan bersifat sementara dan dihapus bersama alamat setelah masa aktif berakhir.']],
                ['@type' => 'Question', 'name' => 'Apakah bisa kirim email?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Tidak. Layanan ini hanya untuk menerima email, bukan mengirim.']],
                ['@type' => 'Question', 'name' => 'Apakah aman untuk OTP?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Ya, untuk verifikasi cepat dan OTP. Jangan gunakan untuk akun penting yang permanen.']],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    <script>
        function emailApp() {
            return {
                email: null,
                token: null,
                expiresAt: null,
                timeLeft: 0,
                messages: [],
                selectedMessage: null,
                expiryMinutes: 10,
                loading: false,
                refreshing: false,
                error: null,
                copied: false,
                timer: null,
                pollTimer: null,
                customUsername: '',
                customDomain: '',
                domains: window.domains || [],

                randomizeUsername() {
                    const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
                    let result = '';
                    for (let i = 0; i < 8; i++) {
                        result += chars.charAt(Math.floor(Math.random() * chars.length));
                    }
                    this.customUsername = result;
                },

                init() {
                    if (this.domains.length > 0) {
                        this.customDomain = this.domains[0];
                    }
                    this.randomizeUsername();

                    const saved = localStorage.getItem('emailtemp');
                    if (saved) {
                        try {
                            const d = JSON.parse(saved);
                            if (d.expiresAt && new Date(d.expiresAt) > new Date()) {
                                this.email = d.email;
                                this.token = d.token;
                                this.expiresAt = new Date(d.expiresAt);
                                this.startTimers();
                                this.refresh();
                            } else {
                                localStorage.removeItem('emailtemp');
                            }
                        } catch (e) {
                            localStorage.removeItem('emailtemp');
                        }
                    }
                },

                async generate() {
                    this.loading = true;
                    this.error = null;
                    try {
                        const res = await this.api('/api/v1/generate', {
                            method: 'POST',
                            body: JSON.stringify({
                                expiry_minutes: parseInt(this.expiryMinutes),
                                username: this.customUsername,
                                domain: this.customDomain
                            }),
                        });
                        if (!res.success) throw new Error(res.message || 'Failed');
                        this.email = res.email;
                        this.token = res.token;
                        this.expiresAt = new Date(res.expires_at);
                        this.messages = [];
                        this.selectedMessage = null;
                        localStorage.setItem('emailtemp', JSON.stringify({
                            email: this.email,
                            token: this.token,
                            expiresAt: res.expires_at
                        }));
                        this.startTimers();
                    } catch (e) {
                        this.error = e.message;
                    }
                    this.loading = false;
                },

                async refresh() {
                    if (!this.token) return;
                    this.refreshing = true;
                    try {
                        const res = await this.api(`/api/v1/inbox/${this.token}`);
                        if (res.success) {
                            this.messages = res.messages;
                            this.expiresAt = new Date(res.expires_at);
                        }
                    } catch (e) {
                        if (e.message.includes('404')) this.expire();
                        else this.error = 'Gagal memuat inbox. Coba lagi.';
                    }
                    this.refreshing = false;
                },

                async openMessage(id) {
                    try {
                        const res = await this.api(`/api/v1/inbox/${this.token}/messages/${id}`);
                        if (res.success) {
                            this.selectedMessage = res.message;
                            const msg = this.messages.find(m => m.id === id);
                            if (msg) msg.is_read = true;
                        }
                    } catch (e) {
                        this.error = 'Pesan gagal dibuka. Coba lagi.';
                    }
                },

                async deleteMessage(id) {
                    try {
                        await this.api(`/api/v1/inbox/${this.token}/messages/${id}`, {
                            method: 'DELETE'
                        });
                        this.messages = this.messages.filter(m => m.id !== id);
                        this.selectedMessage = null;
                    } catch (e) {
                        this.error = 'Pesan gagal dihapus. Coba lagi.';
                    }
                },

                async deleteEmail() {
                    if (!confirm('Hapus email sementara dan semua pesan?')) return;
                    try {
                        await this.api(`/api/v1/email/${this.token}`, {
                            method: 'DELETE'
                        });
                    } catch (e) {}
                    this.expire();
                },

                async api(url, opts = {}) {
                    const res = await fetch(url, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        credentials: 'same-origin',
                        ...opts,
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data.message || res.status);
                    return data;
                },

                async copyEmail() {
                    try {
                        if (navigator.clipboard?.writeText) {
                            await navigator.clipboard.writeText(this.email);
                        } else {
                            const input = document.createElement('textarea');
                            input.value = this.email;
                            input.setAttribute('readonly', '');
                            input.style.position = 'fixed';
                            input.style.opacity = '0';
                            document.body.appendChild(input);
                            input.select();
                            document.execCommand('copy');
                            input.remove();
                        }
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    } catch (e) {
                        this.error = 'Gagal menyalin. Pilih alamat secara manual.';
                    }
                },

                startTimers() {
                    this.updateTimeLeft();
                    this.timer = setInterval(() => this.updateTimeLeft(), 1000);
                    this.pollTimer = setInterval(() => this.refresh(), 10000);
                },

                updateTimeLeft() {
                    if (!this.expiresAt) return;
                    this.timeLeft = Math.max(0, Math.floor((this.expiresAt - new Date()) / 1000));
                    if (this.timeLeft <= 0) this.expire();
                },

                expire() {
                    clearInterval(this.timer);
                    clearInterval(this.pollTimer);
                    this.email = null;
                    this.token = null;
                    this.messages = [];
                    this.selectedMessage = null;
                    localStorage.removeItem('emailtemp');
                },

                formatTimeLeft() {
                    const m = Math.floor(this.timeLeft / 60);
                    const s = this.timeLeft % 60;
                    return `${m}:${String(s).padStart(2,'0')}`;
                },

                formatDate(d) {
                    if (!d) return '';
                    const date = new Date(d);
                    const now = new Date();
                    if (date.toDateString() === now.toDateString()) {
                        return date.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    return date.toLocaleDateString([], {
                        month: 'short',
                        day: 'numeric'
                    });
                },

                formatBytes(b) {
                    if (!b) return '';
                    if (b < 1024) return b + ' B';
                    return (b / 1024).toFixed(1) + ' KB';
                },

                showQr: false,
            }
        }
    </script>

    {{-- QR Code Modal --}}
    <div x-show="showQr" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
        @keydown.escape.window="showQr = false" @click.self="showQr = false">
        <div
            class="glass-card rounded-2xl border border-slate-200 dark:border-slate-700 p-6 max-w-sm w-full shadow-2xl text-center">
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 mb-2">QR Code Alamat Email</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 break-all font-mono" x-text="email"></p>
            <div class="bg-white p-3 rounded-xl inline-block shadow-inner mb-4">
                <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=${encodeURIComponent(email || '')}`"
                    alt="QR Code Alamat Email" width="176" height="176" loading="lazy"
                    class="w-44 h-44 mx-auto rounded">
            </div>
            <div>
                <button type="button" @click="showQr = false"
                    class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-sm font-semibold text-slate-700 dark:text-slate-200 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</body>

</html>
