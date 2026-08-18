<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Email sementara gratis untuk OTP, testing, dan inbox sekali pakai tanpa registrasi.">
    <meta name="theme-color" content="#00a8e8">
    <link rel="canonical" href="{{ url('/') }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}">
    <meta property="og:title"
        content="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }} — Email sementara tanpa registrasi">
    <meta property="og:description"
        content="Email sementara gratis untuk OTP, testing, dan inbox sekali pakai tanpa registrasi.">
    <meta property="og:url" content="{{ url('/') }}">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title"
        content="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }} — Email sementara tanpa registrasi">
    <meta name="twitter:description"
        content="Email sementara gratis untuk OTP, testing, dan inbox sekali pakai tanpa registrasi.">
    <title>{{ \App\Models\Setting::get('app_name', 'EmailTemp') }} — Email sementara tanpa registrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Mono:wght@400;700&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            color-scheme: light;
        }

        [x-cloak] {
            display: none !important
        }

        ::selection {
            background: #f3b23c;
            color: #101820;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background: #f7fafc;
        }

        button,
        select {
            -webkit-tap-highlight-color: transparent;
        }

        button:focus-visible,
        select:focus-visible {
            outline: 3px solid #f3b23c;
            outline-offset: 3px;
        }

        .inbox-surface {
            box-shadow: 0 20px 55px rgba(16, 24, 32, .10);
        }

        .mono {
            font-family: 'Space Mono', monospace;
        }

        .grid-signal {
            background-image: linear-gradient(rgba(255, 255, 255, .045) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, .045) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .email-frame {
            min-height: 320px;
            width: 100%;
            border: 0;
            background: white;
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
            '@type' => 'WebApplication',
            'name' => \App\Models\Setting::get('app_name', 'EmailTemp'),
            'url' => url('/'),
            'applicationCategory' => 'UtilitiesApplication',
            'operatingSystem' => 'Web',
            'description' => 'Email sementara gratis untuk OTP, testing, dan inbox sekali pakai tanpa registrasi.',
            'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</head>

<body class="h-full font-sans text-slate-900">

    <script>
        window.domains = @json($domains ?? []);
    </script>

    <!--
        THESIS: Disposable email should feel like a fast, trustworthy utility, not a marketing maze.
        OWN-WORLD: Signal-lab navy controls, amber action states, paper workspace, mono lifecycle labels.
        STORY: Visitor understands privacy, creates an address, copies it, then watches one focused inbox.
        FIRST VIEWPORT: Header and generator sit inside a navy signal field; one primary action owns center stage.
        FORM: Operate-first inbox workspace; one-file Blade surface preserving existing API behavior.
        FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
    -->

    <div x-data="emailApp()" x-cloak class="min-h-full">
        {{-- Header --}}
        <header class="sticky top-0 z-30 border-b border-slate-100 bg-white/90 backdrop-blur-md transition-all">
            <div class="mx-auto flex h-[72px] max-w-6xl items-center justify-between px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group"
                    aria-label="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }} home">
                    @if (\App\Models\Setting::get('app_logo_url'))
                        <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                            alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}" class="h-9 w-auto">
                    @else
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#00a8e8] to-sky-500 shadow-md shadow-sky-100/30 group-hover:scale-105 transition-transform duration-200">
                            <svg class="h-5.5 w-5.5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5" />
                            </svg>
                        </div>
                        <span
                            class="text-xl font-bold tracking-tight text-slate-800">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</span>
                    @endif
                </a>
                <div class="flex items-center gap-6">
                    <nav class="hidden items-center gap-1 sm:flex">
                        <a href="#cara-kerja"
                            class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] px-3.5 py-2 rounded-xl hover:bg-slate-50 transition-all duration-150">Cara
                            kerja</a>
                        <a href="#faq"
                            class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] px-3.5 py-2 rounded-xl hover:bg-slate-50 transition-all duration-150">FAQ</a>
                        <a href="{{ route('legal.privacy') }}"
                            class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] px-3.5 py-2 rounded-xl hover:bg-slate-50 transition-all duration-150">Privasi</a>
                    </nav>
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:text-slate-900 active:bg-slate-100 transition-all duration-150">
                        Buat Baru
                    </a>
                </div>
            </div>
        </header>

        {{-- Main --}}
        <main class="mx-auto max-w-6xl px-5 py-8 sm:py-12 lg:px-8">

            <div class="mx-auto mb-8 max-w-3xl text-center">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-5xl">Email sementara gratis</h1>
                <p class="mx-auto mt-3 max-w-2xl leading-7 text-slate-500">Buat alamat email sekali pakai tanpa
                    registrasi. Terima OTP, verifikasi, dan email testing langsung di inbox ini.</p>
            </div>

            {{-- Generate Section --}}
            <template x-if="!email">
                <div
                    class="mx-auto max-w-2xl rounded-3xl border border-slate-100 bg-white p-8 text-center shadow-[0_12px_40px_-12px_rgba(0,0,0,0.05)] sm:p-12">
                    <div
                        class="mx-auto mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-[#00a8e8]">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Buat alamat kustom</h2>
                    <p class="mx-auto mb-8 mt-2 max-w-md text-sm text-slate-500">Tentukan nama email dan domain pilihan
                        Anda di bawah ini, tanpa pendaftaran.</p>

                    <!-- Email Customizer (Unified Input) -->
                    <div
                        class="mb-8 flex flex-col sm:flex-row items-stretch justify-between rounded-2xl border border-slate-200 bg-slate-50/50 p-1.5 focus-within:border-[#00a8e8] focus-within:ring-4 focus-within:ring-sky-50 transition-all gap-1 sm:gap-0">
                        <div class="relative flex-1 flex items-center">
                            <input type="text" x-model="customUsername" placeholder="username"
                                class="w-full bg-transparent border-0 pl-4 pr-10 py-3 text-lg font-mono font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-0 sm:text-right"
                                aria-label="Username kustom">
                            <button @click="randomizeUsername()"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#00a8e8] transition-colors"
                                title="Acak username">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                        </div>
                        <div class="hidden sm:flex items-center justify-center text-slate-400 font-medium text-lg px-2">
                            @</div>
                        <div class="flex-1 sm:max-w-[240px] flex items-center">
                            <span class="sm:hidden text-slate-400 font-medium text-lg pl-4 pr-2">@</span>
                            <select x-model="customDomain"
                                class="w-full bg-transparent border-0 px-4 sm:px-3 py-3 text-lg font-mono font-medium text-slate-800 focus:outline-none focus:ring-0 cursor-pointer"
                                aria-label="Domain kustom">
                                <template x-for="dom in domains" :key="dom">
                                    <option :value="dom" x-text="dom"></option>
                                </template>
                                <option x-show="domains.length === 0" value="">(No active domains)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Expiry Selector (Interactive Pills) -->
                    <div class="mb-8">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">Masa Aktif Inbox
                        </p>
                        <div class="inline-flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                            <button type="button" @click="expiryMinutes = 10"
                                :class="expiryMinutes == 10 ? 'bg-white text-slate-900 shadow-sm font-semibold' :
                                    'text-slate-500 hover:text-slate-900 font-medium'"
                                class="px-4 py-2 rounded-lg text-xs transition-all duration-150">
                                10 Menit
                            </button>
                            <button type="button" @click="expiryMinutes = 30"
                                :class="expiryMinutes == 30 ? 'bg-white text-slate-900 shadow-sm font-semibold' :
                                    'text-slate-500 hover:text-slate-900 font-medium'"
                                class="px-4 py-2 rounded-lg text-xs transition-all duration-150">
                                30 Menit
                            </button>
                            <button type="button" @click="expiryMinutes = 60"
                                :class="expiryMinutes == 60 ? 'bg-white text-slate-900 shadow-sm font-semibold' :
                                    'text-slate-500 hover:text-slate-900 font-medium'"
                                class="px-4 py-2 rounded-lg text-xs transition-all duration-150">
                                1 Jam
                            </button>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button @click="generate()" :disabled="loading || !customUsername || !customDomain"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-[#00a8e8] px-10 py-4 font-semibold text-white shadow-lg shadow-sky-100 transition-all hover:bg-sky-600 hover:shadow-sky-200 hover:-translate-y-[1px] active:translate-y-0 disabled:opacity-50 disabled:pointer-events-none">
                        <svg x-show="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                        </svg>
                        <span x-text="loading ? 'Membuat...' : 'Buat Alamat Email'"></span>
                    </button>

                    <p x-show="error" x-text="error" role="alert" class="mt-4 text-sm text-red-600 font-medium">
                    </p>
                </div>
            </template>

            {{-- Inbox Section --}}
            <template x-if="email">
                <div>
                    {{-- Email Address Card --}}
                    <div
                        class="bg-white rounded-3xl border border-slate-100 shadow-[0_12px_40px_-12px_rgba(0,0,0,0.04)] p-6 mb-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alamat
                                    Email Anda</p>
                                <div class="flex items-center gap-2.5">
                                    <p class="text-xl sm:text-2xl font-mono font-bold text-slate-800 truncate select-all"
                                        x-text="email"></p>
                                    <button @click="copyEmail()"
                                        class="flex-shrink-0 p-2 rounded-xl bg-slate-50 hover:bg-[#00a8e8]/10 text-slate-400 hover:text-[#00a8e8] transition-all focus:outline-none focus:ring-2 focus:ring-[#00a8e8]/20"
                                        title="Salin alamat email" aria-label="Salin alamat email">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                <div class="text-right">
                                    <span
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-slate-50 text-slate-600 border border-slate-100"
                                        :class="timeLeft < 120 ? 'bg-red-50 text-red-600 border-red-100/50' : ''">
                                        <span class="h-2 w-2 rounded-full animate-pulse"
                                            :class="timeLeft < 120 ? 'bg-red-500' : 'bg-emerald-500'"></span>
                                        <span x-text="formatTimeLeft()"></span>
                                    </span>
                                </div>
                                <button @click="refresh()" :disabled="refreshing"
                                    class="p-3 rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-slate-300 text-slate-500 hover:text-[#00a8e8] transition-all disabled:opacity-50 focus:outline-none focus:ring-2 focus:ring-[#00a8e8]/20"
                                    title="Segarkan kotak masuk" aria-label="Segarkan kotak masuk">
                                    <svg class="w-5 h-5" :class="refreshing && 'animate-spin'" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                <button @click="deleteEmail()"
                                    class="p-3 rounded-xl border border-red-100 bg-red-50/50 hover:bg-red-50 text-red-500 hover:text-red-600 transition-all focus:outline-none focus:ring-2 focus:ring-red-100"
                                    title="Hapus alamat email" aria-label="Hapus alamat email">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p x-show="copied" x-transition
                            class="mt-3 text-xs text-emerald-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Alamat email berhasil disalin!
                        </p>
                    </div>

                    {{-- Messages --}}
                    <div
                        class="bg-white rounded-3xl border border-slate-100 shadow-[0_12px_40px_-12px_rgba(0,0,0,0.04)] overflow-hidden">
                        <div
                            class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/30">
                            <h2 class="text-lg font-bold text-slate-800">Kotak Masuk</h2>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-[#00a8e8] border border-sky-100/50"
                                aria-live="polite" x-text="messages.length + ' pesan'"></span>
                        </div>

                        {{-- Empty State --}}
                        <template x-if="messages.length === 0">
                            <div class="py-16 text-center">
                                <svg class="w-12 h-12 text-slate-300 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-slate-500 font-medium">No messages yet</p>
                                <p class="text-sm text-slate-400 mt-1">Waiting for incoming emails...</p>
                            </div>
                        </template>

                        {{-- Message List --}}
                        <template x-if="messages.length > 0 && !selectedMessage">
                            <div class="divide-y divide-slate-100">
                                <template x-for="msg in messages" :key="msg.id">
                                    <button @click="openMessage(msg.id)"
                                        class="w-full text-left px-5 sm:px-6 py-4 hover:bg-slate-50 transition-colors flex items-start gap-3">
                                        <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0"
                                            :class="msg.is_read ? 'bg-slate-200' : 'bg-indigo-500'"></div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-baseline justify-between gap-2">
                                                <p class="text-sm font-semibold text-slate-800 truncate"
                                                    :class="!msg.is_read && 'text-slate-900'"
                                                    x-text="msg.from_name || msg.from_address"></p>
                                                <span class="text-xs text-slate-400 flex-shrink-0 tabular-nums"
                                                    x-text="formatDate(msg.received_at)"></span>
                                            </div>
                                            <p class="text-sm text-slate-500 truncate mt-0.5"
                                                x-text="msg.subject || '(No Subject)'"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>
                        </template>

                        {{-- Message Detail --}}
                        <template x-if="selectedMessage">
                            <div>
                                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                                    <button @click="selectedMessage = null" aria-label="Back to inbox"
                                        title="Back to inbox"
                                        class="p-1.5 rounded-lg hover:bg-slate-100 text-slate-400 hover:text-slate-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </button>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-slate-800 truncate"
                                            x-text="selectedMessage.subject || '(No Subject)'"></p>
                                        <p class="text-xs text-slate-400"
                                            x-text="(selectedMessage.from_name ? selectedMessage.from_name + ' <' + selectedMessage.from_address + '>' : selectedMessage.from_address)">
                                        </p>
                                    </div>
                                    <button @click="deleteMessage(selectedMessage.id)"
                                        class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-500 transition-colors"
                                        title="Delete message" aria-label="Delete message">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>

                                {{-- Attachments --}}
                                <template x-if="selectedMessage.attachments && selectedMessage.attachments.length > 0">
                                    <div class="px-5 sm:px-6 py-3 border-b border-slate-100 flex flex-wrap gap-2">
                                        <template x-for="att in selectedMessage.attachments" :key="att.id">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-xs font-medium text-slate-600">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </svg>
                                                <span x-text="att.filename"></span>
                                                <span class="text-slate-500"
                                                    x-text="formatBytes(att.size_bytes)"></span>
                                            </span>
                                        </template>
                                    </div>
                                </template>

                                {{-- Body --}}
                                <div class="px-5 sm:px-6 py-5">
                                    <template x-if="selectedMessage.body_html">
                                        <iframe class="email-frame rounded-xl" title="Email HTML content"
                                            sandbox="allow-same-origin" :srcdoc="selectedMessage.body_html"></iframe>
                                    </template>
                                    <template x-if="!selectedMessage.body_html && selectedMessage.body_text">
                                        <pre class="text-sm text-slate-700 whitespace-pre-wrap font-sans" x-text="selectedMessage.body_text"></pre>
                                    </template>
                                    <template x-if="!selectedMessage.body_html && !selectedMessage.body_text">
                                        <p class="text-sm text-slate-400 italic">No content.</p>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <section id="cara-kerja" class="mt-14 grid gap-8 md:grid-cols-2" aria-label="About EmailTemp">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">How it works</h2>
                    <ol class="mt-4 space-y-4 text-sm text-slate-600">
                        <li><strong class="text-slate-900">1. Generate.</strong> Get a random address without creating
                            an account.</li>
                        <li><strong class="text-slate-900">2. Receive.</strong> Keep this page open while verification
                            emails arrive.</li>
                        <li><strong class="text-slate-900">3. Disappear.</strong> Address and messages are removed when
                            the timer ends.</li>
                    </ol>
                </div>
                <div>
                    <h2 id="faq" class="text-xl font-bold text-slate-900">Common questions</h2>
                    <div class="mt-4 space-y-4 text-sm">
                        <details class="border-b border-slate-200 pb-4">
                            <summary class="cursor-pointer font-semibold text-slate-800">Do I need to sign up?
                            </summary>
                            <p class="mt-2 text-slate-600">No. EmailTemp creates a temporary session without login or
                                registration.</p>
                        </details>
                        <details class="border-b border-slate-200 pb-4">
                            <summary class="cursor-pointer font-semibold text-slate-800">How long does an address last?
                            </summary>
                            <p class="mt-2 text-slate-600">Choose 10 minutes, 30 minutes, or 1 hour. Ten minutes is the
                                default.</p>
                        </details>
                        <details class="border-b border-slate-200 pb-4">
                            <summary class="cursor-pointer font-semibold text-slate-800">Are messages saved?</summary>
                            <p class="mt-2 text-slate-600">Messages are temporary and deleted with the address after
                                expiration.</p>
                        </details>
                    </div>
                </div>
            </section>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-slate-100 bg-white pt-16 pb-12 mt-16">
            <div class="mx-auto max-w-6xl px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 pb-12 border-b border-slate-100">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-3">
                            @if (\App\Models\Setting::get('app_logo_url'))
                                <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                                    alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}" class="h-9 w-auto">
                            @else
                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-[#00a8e8]">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span
                                    class="text-lg font-bold tracking-tight text-slate-800">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</span>
                            @endif
                        </div>
                        <p class="mt-4 text-sm text-slate-500 max-w-sm leading-relaxed">
                            Layanan email sekali pakai gratis untuk verifikasi cepat, pengujian aplikasi, dan
                            menghindari spam tanpa mendaftar.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tautan Cepat</h3>
                        <ul class="mt-4 space-y-2.5">
                            <li><a href="#cara-kerja"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Cara
                                    Kerja</a></li>
                            <li><a href="#faq"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Pertanyaan
                                    Umum (FAQ)</a></li>
                            <li><a href="{{ route('home') }}"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Mulai
                                    Ulang</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hukum & Legal</h3>
                        <ul class="mt-4 space-y-2.5">
                            <li><a href="{{ route('legal.terms') }}"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Syarat
                                    & Ketentuan</a></li>
                            <li><a href="{{ route('legal.privacy') }}"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Kebijakan
                                    Privasi</a></li>
                            <li><a href="{{ route('legal.cookies') }}"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Kebijakan
                                    Cookie</a></li>
                            <li><a href="{{ route('legal.contact') }}"
                                    class="text-sm font-medium text-slate-600 hover:text-[#00a8e8] transition-colors">Hubungi
                                    Kami</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                    <p>{{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' EmailTemp. Semua email dihapus otomatis setelah masa aktif berakhir.') }}
                    </p>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        <span class="font-medium text-slate-500">Semua sistem operasional</span>
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
                ['@type' => 'Question', 'name' => 'Do I need to sign up?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'No. EmailTemp creates a temporary session without login or registration.']],
                ['@type' => 'Question', 'name' => 'How long does an address last?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Choose 10 minutes, 30 minutes, or 1 hour. Ten minutes is the default.']],
                ['@type' => 'Question', 'name' => 'Are messages saved?', 'acceptedAnswer' => ['@type' => 'Answer', 'text' => 'Messages are temporary and deleted with the address after expiration.']],
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

                    // Restore session
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
                        else this.error = 'Inbox refresh failed. Try again.';
                    }
                    this.refreshing = false;
                },

                async openMessage(id) {
                    try {
                        const res = await this.api(`/api/v1/inbox/${this.token}/messages/${id}`);
                        if (res.success) {
                            this.selectedMessage = res.message;
                            // Mark read in list
                            const msg = this.messages.find(m => m.id === id);
                            if (msg) msg.is_read = true;
                        }
                    } catch (e) {
                        this.error = 'Message could not be opened. Try again.';
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
                        this.error = 'Message could not be deleted. Try again.';
                    }
                },

                async deleteEmail() {
                    if (!confirm('Delete this temporary email and all messages?')) return;
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
                        this.error = 'Copy failed. Select the address manually.';
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
            }
        }
    </script>
</body>

</html>
