<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="dark && 'dark'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup — EmailTemp</title>
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
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white mb-3">EmailTemp</h2>
                <p class="text-indigo-200 text-lg leading-relaxed max-w-sm mx-auto">Welcome! Let's set up your temporary
                    email service in just a few steps.</p>
            </div>
        </div>

        {{-- Right setup form --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12 bg-slate-50 dark:bg-slate-900 transition-colors">
            <div class="w-full max-w-lg" x-data="setupWizard()" x-cloak>

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
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">EmailTemp Setup</h2>
                </div>

                {{-- Step indicator --}}
                <div class="flex items-center justify-center gap-3 mb-8">
                    <template x-for="s in 3" :key="s">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-semibold transition-all duration-300"
                                :class="step >= s ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' :
                                    'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400'">
                                <span x-text="s"></span>
                            </div>
                            <template x-if="s < 3">
                                <div class="w-12 h-0.5 rounded transition-all duration-300"
                                    :class="step > s ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700'"></div>
                            </template>
                        </div>
                    </template>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 p-8">

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

                    <form method="POST" action="{{ route('setup.store') }}">
                        @csrf

                        {{-- Step 1: Welcome & Guide --}}
                        <div x-show="step === 1" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Selamat Datang!</h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Wizard ini akan memandu Anda
                                    menyiapkan EmailTemp
                                    dalam beberapa langkah.</p>
                            </div>

                            <div class="mb-5">
                                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Yang akan
                                    dilakukan wizard ini:</h3>
                                <div class="space-y-2.5">
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                                        <div class="text-sm text-slate-600 dark:text-slate-300">
                                            <span class="font-medium text-slate-700 dark:text-slate-200">Buat akun
                                                admin</span> — akun untuk mengelola aplikasi.
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                                        <div class="text-sm text-slate-600 dark:text-slate-300">
                                            <span class="font-medium text-slate-700 dark:text-slate-200">Tambah domain
                                                email</span> — domain yang akan digunakan untuk temporary email.
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <span
                                            class="w-6 h-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                                        <div class="text-sm text-slate-600 dark:text-slate-300">
                                            <span class="font-medium text-slate-700 dark:text-slate-200">Deploy
                                                Cloudflare Worker</span> — worker untuk menerima email masuk.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 mb-5">
                                <h3
                                    class="text-sm font-semibold text-amber-800 dark:text-amber-300 mb-2 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Prasyarat
                                </h3>
                                <ul class="text-xs text-amber-700 dark:text-amber-400 space-y-1.5">
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Database sudah dikonfigurasi dan migration sudah dijalankan
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Redis tersedia untuk queue dan caching
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Domain sudah ditambahkan ke Cloudflare (untuk email routing)
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5 text-amber-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Cloudflare API Token dengan permission <code
                                            class="bg-amber-100 dark:bg-amber-900/50 px-1 rounded">Workers Scripts:
                                            Edit</code>
                                    </li>
                                </ul>
                            </div>

                            <div
                                class="bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl p-4 mb-6">
                                <h3
                                    class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Alternatif via CLI
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Anda juga bisa menggunakan
                                    wizard CLI sebagai pengganti setup ini:</p>
                                <pre class="text-xs font-mono bg-slate-900 text-slate-100 px-3 py-2 rounded-lg select-all">php artisan app:install</pre>
                            </div>

                            <div class="flex justify-end">
                                <button type="button" @click="step = 2"
                                    class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-150 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5"
                                    style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                                    Mulai Setup
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Step 2: Admin Account --}}
                        <div x-show="step === 2" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Create Admin Account
                                </h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Set up your administrator account
                                    to manage the application.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="name"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Full
                                        Name</label>
                                    <input id="name" name="name" type="text" required x-model="form.name"
                                        value="{{ old('name') }}" placeholder="Admin"
                                        class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                                </div>
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Email
                                        Address</label>
                                    <input id="email" name="email" type="email" required
                                        x-model="form.email" value="{{ old('email') }}"
                                        placeholder="admin@example.com"
                                        class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                                </div>
                                <div>
                                    <label for="password"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                                    <input id="password" name="password" type="password" required
                                        x-model="form.password" placeholder="Minimum 8 characters"
                                        class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                                </div>
                                <div>
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Confirm
                                        Password</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        required placeholder="Re-enter password"
                                        class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" @click="step = 1"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Back
                                </button>
                                <button type="button" @click="nextStep()"
                                    class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-150 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5"
                                    style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                                    Next Step
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Step 3: Domain (optional) --}}
                        <div x-show="step === 3" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-x-4"
                            x-transition:enter-end="opacity-100 translate-x-0">
                            <div class="mb-6">
                                <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Add Email Domain
                                </h1>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Add your first domain for
                                    temporary emails. You can skip this and add it later from Settings.</p>
                            </div>

                            {{-- DNS Setup Guide --}}
                            <div x-data="{ showGuide: false }" class="mb-5">
                                <button type="button" @click="showGuide = !showGuide"
                                    class="w-full flex items-center justify-between gap-2 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 px-4 py-3 text-left transition-colors hover:bg-blue-100 dark:hover:bg-blue-900/30">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-semibold text-blue-800 dark:text-blue-300">How to set
                                            up your domain for Email Routing</span>
                                    </span>
                                    <svg class="w-4 h-4 text-blue-500 transition-transform"
                                        :class="showGuide && 'rotate-180'" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="showGuide" x-collapse class="mt-3 space-y-3">
                                    <div class="flex gap-3 items-start">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</span>
                                        <div class="text-xs text-slate-700 dark:text-slate-300">
                                            <p class="font-semibold mb-1">Add domain to Cloudflare</p>
                                            <p>Go to <a href="https://dash.cloudflare.com" target="_blank"
                                                    class="text-blue-600 dark:text-blue-400 underline hover:no-underline">Cloudflare
                                                    Dashboard</a> → <strong>Add a site</strong> → enter your domain →
                                                select a plan.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 items-start">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</span>
                                        <div class="text-xs text-slate-700 dark:text-slate-300">
                                            <p class="font-semibold mb-1">Update nameservers</p>
                                            <p>Point your domain's nameservers to the ones provided by Cloudflare at
                                                your domain registrar.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 items-start">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</span>
                                        <div class="text-xs text-slate-700 dark:text-slate-300">
                                            <p class="font-semibold mb-1">Enable Email Routing</p>
                                            <p>In Cloudflare Dashboard → your domain → <strong>Email</strong> →
                                                <strong>Email Routing</strong> → Enable.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 items-start">
                                        <span
                                            class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-400 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">4</span>
                                        <div class="text-xs text-slate-700 dark:text-slate-300">
                                            <p class="font-semibold mb-1">Set Catch-All to Worker</p>
                                            <p>In Email Routing → <strong>Routing rules</strong> →
                                                <strong>Catch-all</strong> → set action to <strong>Send to a
                                                    Worker</strong>.</p>
                                        </div>
                                    </div>

                                    <div
                                        class="rounded-lg bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 p-3">
                                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-300 mb-2">DNS
                                            Records (auto-added by Cloudflare Email Routing):</p>
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-xs text-slate-600 dark:text-slate-300">
                                                <thead>
                                                    <tr class="border-b border-slate-200 dark:border-slate-600">
                                                        <th class="text-left py-1 pr-2 font-medium">Type</th>
                                                        <th class="text-left py-1 pr-2 font-medium">Name</th>
                                                        <th class="text-left py-1 font-medium">Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="font-mono">
                                                    <tr class="border-b border-slate-100 dark:border-slate-600">
                                                        <td class="py-1 pr-2"><span
                                                                class="bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 px-1.5 py-0.5 rounded text-[10px] font-bold">MX</span>
                                                        </td>
                                                        <td class="py-1 pr-2">@</td>
                                                        <td class="py-1">route1.mx.cloudflare.net (Priority: 69)</td>
                                                    </tr>
                                                    <tr class="border-b border-slate-100 dark:border-slate-600">
                                                        <td class="py-1 pr-2"><span
                                                                class="bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 px-1.5 py-0.5 rounded text-[10px] font-bold">MX</span>
                                                        </td>
                                                        <td class="py-1 pr-2">@</td>
                                                        <td class="py-1">route2.mx.cloudflare.net (Priority: 27)</td>
                                                    </tr>
                                                    <tr class="border-b border-slate-100 dark:border-slate-600">
                                                        <td class="py-1 pr-2"><span
                                                                class="bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-400 px-1.5 py-0.5 rounded text-[10px] font-bold">MX</span>
                                                        </td>
                                                        <td class="py-1 pr-2">@</td>
                                                        <td class="py-1">route3.mx.cloudflare.net (Priority: 93)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="py-1 pr-2"><span
                                                                class="bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-400 px-1.5 py-0.5 rounded text-[10px] font-bold">TXT</span>
                                                        </td>
                                                        <td class="py-1 pr-2">@</td>
                                                        <td class="py-1">v=spf1 include:_spf.mx.cloudflare.net ~all
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="domain"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1.5">Domain
                                    Name</label>
                                <input id="domain" name="domain" type="text" value="{{ old('domain') }}"
                                    placeholder="example.com"
                                    class="block w-full rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 px-4 py-3 text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 transition-all duration-150 focus:border-indigo-500 focus:bg-white dark:focus:bg-slate-600 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                                <p class="mt-2 text-xs text-slate-400 dark:text-slate-500">Optional — you can add
                                    domains later from the admin panel.</p>
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <button type="button" @click="step = 2"
                                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Back
                                </button>
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-150 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5"
                                    style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    Complete Setup
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setupWizard() {
            return {
                step: {{ $errors->any() ? 2 : 1 }},
                form: {
                    name: '{{ old('name', '') }}',
                    email: '{{ old('email', '') }}',
                    password: '',
                },
                nextStep() {
                    if (!this.form.name.trim() || !this.form.email.trim() || !this.form.password.trim()) {
                        return;
                    }
                    if (this.form.password.length < 8) {
                        return;
                    }
                    this.step = 3;
                },
            }
        }
    </script>
</body>

</html>
