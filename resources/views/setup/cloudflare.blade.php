<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="dark && 'dark'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cloudflare Worker Setup — EmailTemp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-sans bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="flex min-h-full">
        {{-- Left branding panel --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center"
            style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 100%)">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-indigo-300 rounded-full blur-3xl"></div>
            </div>
            <div class="relative z-10 text-center px-12">
                <div
                    class="w-20 h-20 mx-auto rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-8 shadow-2xl shadow-indigo-900/30">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-3">Cloudflare Worker</h2>
                <p class="text-indigo-200 text-lg leading-relaxed max-w-sm mx-auto">Deploy the email receiver worker to
                    start receiving emails via Cloudflare Email Routing.</p>
            </div>
        </div>

        {{-- Right setup form --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12 bg-slate-50 dark:bg-slate-900 transition-colors">
            <div class="w-full max-w-lg">

                {{-- Theme toggle --}}
                <div class="flex justify-end mb-4">
                    <button type="button" @click="dark = !dark; localStorage.setItem('theme', dark ? 'dark' : 'light')"
                        class="p-2 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 transition-colors shadow-sm"
                        :title="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                        <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                </div>

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-4 shadow-lg"
                        style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">Cloudflare Worker Setup</h2>
                </div>

                {{-- Step indicator --}}
                <div class="flex items-center justify-center gap-3 mb-8">
                    <template x-data>
                        @for ($s = 1; $s <= 3; $s++)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300 {{ $s <= 3 ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400' }}">
                                    @if ($s < 3)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        {{ $s }}
                                    @endif
                                </div>
                                @if ($s < 3)
                                    <div class="w-12 h-0.5 rounded bg-indigo-600"></div>
                                @endif
                            </div>
                        @endfor
                    </template>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 p-8">

                    @if (session('error'))
                        <div
                            class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm text-red-800 dark:text-red-300">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div
                            class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <ul class="text-sm text-red-800 dark:text-red-300 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Deploy Cloudflare Worker</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Configure your Cloudflare credentials to
                            deploy the email receiver worker. You can skip this and configure it later from Admin
                            Settings.</p>
                    </div>

                    {{-- How to get credentials --}}
                    <div
                        class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-6">
                        <h3
                            class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            How to get credentials
                        </h3>
                        <ul class="text-xs text-amber-700 dark:text-amber-400 space-y-1.5">
                            <li><strong>API Token:</strong> Go to <a
                                    href="https://dash.cloudflare.com/profile/api-tokens" target="_blank"
                                    class="underline hover:no-underline">Cloudflare Dashboard → API Tokens</a> → Create
                                Token with <code class="bg-amber-100 dark:bg-amber-900/50 px-1 rounded">Account.Workers
                                    Scripts:
                                    Edit</code> permission.</li>
                            <li><strong>Account ID:</strong> Found on your <a href="https://dash.cloudflare.com"
                                    target="_blank" class="underline hover:no-underline">Cloudflare Dashboard</a>
                                overview page sidebar.</li>
                        </ul>
                    </div>

                    <div
                        class="bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl p-4 mb-6">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-2">Setelah deploy</h3>
                        <ol class="text-xs text-slate-600 dark:text-slate-300 space-y-1.5 list-decimal list-inside">
                            <li>Pastikan Worker membuka endpoint <code
                                    class="bg-slate-200 dark:bg-slate-600 px-1 rounded">/health</code> dan
                                mengembalikan status <code
                                    class="bg-slate-200 dark:bg-slate-600 px-1 rounded">ok</code>.</li>
                            <li>Cloudflare Dashboard → domain → Email → Email Routing.</li>
                            <li>Aktifkan Catch-All dengan action <strong>Send to Worker</strong> → pilih <code
                                    class="bg-slate-200 dark:bg-slate-600 px-1 rounded">email-worker</code>.</li>
                            <li>Kirim email test ke alamat temporary, lalu refresh inbox.</li>
                        </ol>
                    </div>

                    <form method="POST" action="{{ route('setup.deploy-worker') }}" x-data="{ deploying: false }"
                        @submit="deploying = true">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="cloudflare_api_token"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Cloudflare
                                    API Token</label>
                                <input id="cloudflare_api_token" name="cloudflare_api_token" type="password"
                                    value="{{ old('cloudflare_api_token') }}" placeholder="Your Cloudflare API token"
                                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                            <div>
                                <label for="cloudflare_account_id"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Cloudflare
                                    Account ID</label>
                                <input id="cloudflare_account_id" name="cloudflare_account_id" type="text"
                                    value="{{ old('cloudflare_account_id') }}"
                                    placeholder="Your Cloudflare account ID"
                                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <a href="{{ route('setup.complete') }}"
                                class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                                Skip for now
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            <button type="submit" :disabled="deploying"
                                class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-150 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed"
                                style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                                <template x-if="!deploying">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96z" />
                                        </svg>
                                        Deploy Worker
                                    </span>
                                </template>
                                <template x-if="deploying">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Deploying...
                                    </span>
                                </template>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
