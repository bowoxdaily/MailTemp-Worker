<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#101820">
    <title>{{ $title ?? 'Legal' }} — {{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #f7fafc;
        }

        a:focus-visible {
            outline: 3px solid #f3b23c;
            outline-offset: 3px;
        }
    </style>
</head>

<body class="min-h-screen text-slate-900">
    <header class="border-b border-slate-100 bg-white/90 backdrop-blur-md sticky top-0 z-30">
        <div class="mx-auto flex h-[72px] max-w-4xl items-center justify-between px-6">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group"
                aria-label="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }} home">
                @if (\App\Models\Setting::get('app_logo_url'))
                    <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                        alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}" class="h-9 w-auto">
                @else
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-[#00a8e8] to-sky-500 shadow-md shadow-sky-100/30 group-hover:scale-105 transition-transform duration-200">
                        <svg class="h-5.5 w-5.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 19v-8.93a2 2 0 01.89-1.664l8-5.333a2 2 0 012.22 0l8 5.333A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5" />
                        </svg>
                    </div>
                    <span
                        class="text-xl font-bold tracking-tight text-slate-800">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</span>
                @endif
            </a>
            <a href="{{ route('home') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 hover:text-slate-900 active:bg-slate-100 transition-all duration-150 gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </header>
    <main class="mx-auto max-w-3xl px-6 py-12 sm:py-16">
        <p class="mb-3 text-xs font-bold uppercase tracking-[.18em] text-[#00a8e8]">
            {{ strtoupper(\App\Models\Setting::get('app_name', 'EmailTemp')) }} / LEGAL</p>
        <h1 class="text-4xl font-bold tracking-tight text-slate-800">{{ $title }}</h1>
        <p class="mt-3 text-sm text-slate-500">Terakhir diperbarui: {{ date('d F Y') }}</p>
        <article class="prose prose-slate mt-10 max-w-none leading-7">{{ $slot }}</article>
    </main>
    <footer class="border-t border-slate-100 bg-white py-12">
        <div
            class="mx-auto max-w-3xl px-6 flex flex-col sm:flex-row items-center justify-between gap-6 text-xs text-slate-400">
            <div class="flex flex-wrap gap-x-5 gap-y-2">
                <a href="{{ route('legal.terms') }}"
                    class="font-medium text-slate-500 hover:text-[#00a8e8] transition-colors">Syarat & Ketentuan</a>
                <a href="{{ route('legal.privacy') }}"
                    class="font-medium text-slate-500 hover:text-[#00a8e8] transition-colors">Kebijakan Privasi</a>
                <a href="{{ route('legal.cookies') }}"
                    class="font-medium text-slate-500 hover:text-[#00a8e8] transition-colors">Kebijakan Cookie</a>
                <a href="{{ route('legal.contact') }}"
                    class="font-medium text-slate-500 hover:text-[#00a8e8] transition-colors">Hubungi Kami</a>
            </div>
            <p>{{ \App\Models\Setting::get('footer_copyright', '© ' . date('Y') . ' EmailTemp. Semua email dihapus otomatis setelah masa aktif berakhir.') }}
            </p>
        </div>
    </footer>
</body>

</html>
