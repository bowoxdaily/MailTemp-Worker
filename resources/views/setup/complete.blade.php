<!DOCTYPE html>
<html lang="en" class="h-full" x-data="{ dark: localStorage.getItem('theme') === 'dark' }" :class="dark && 'dark'">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Complete — EmailTemp</title>
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
                <p class="text-indigo-200 text-lg leading-relaxed max-w-sm mx-auto">Your temporary email service is
                    ready to go!</p>
            </div>
        </div>

        {{-- Right completion panel --}}
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
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-slate-900/50 border border-slate-200/60 dark:border-slate-700/60 p-8">
                    {{-- Success icon --}}
                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1">Setup Complete!</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Your EmailTemp application is ready to
                            use.</p>
                    </div>

                    {{-- Next steps --}}
                    @if (session('worker_deployed'))
                        <div
                            class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-5 mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">Cloudflare
                                    Worker deployed
                                    successfully!</span>
                            </div>
                        </div>
                    @endif

                    <div
                        class="bg-slate-50 dark:bg-slate-700/50 rounded-xl border border-slate-200 dark:border-slate-600 p-5 mb-6">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200 mb-3">Next Steps</h3>
                        <ul class="space-y-3 text-sm text-slate-600 dark:text-slate-300">
                            <li class="flex items-start gap-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">1</span>
                                <span>Log in to the <strong>Admin Dashboard</strong> with your new account.</span>
                            </li>
                            @if (!session('worker_deployed'))
                                <li class="flex items-start gap-3">
                                    <span
                                        class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">2</span>
                                    <span>Configure <strong>Cloudflare Worker</strong> credentials in Settings and
                                        deploy the worker.</span>
                                </li>
                            @endif
                            <li class="flex items-start gap-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">{{ session('worker_deployed') ? '2' : '3' }}</span>
                                <span>Configure <strong>Cloudflare Email Routing</strong> to forward emails to your
                                    worker.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span
                                    class="w-5 h-5 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0 text-xs font-bold mt-0.5">{{ session('worker_deployed') ? '3' : '4' }}</span>
                                <span>Open Worker <code
                                        class="bg-slate-200 dark:bg-slate-600 px-1 rounded">/health</code>, then send
                                    one test email and confirm it appears in inbox.</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Production Deployment Guide --}}
                    <div x-data="{ open: false }" class="mb-6">
                        <button type="button" @click="open = !open"
                            class="w-full flex items-center justify-between gap-2 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 px-4 py-3 text-left transition-colors hover:bg-amber-100 dark:hover:bg-amber-900/30">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span class="text-sm font-semibold text-amber-800 dark:text-amber-300">Panduan
                                    Deployment Production</span>
                            </span>
                            <svg class="w-4 h-4 text-amber-500 transition-transform" :class="open && 'rotate-180'"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="mt-3 space-y-4">
                            {{-- Crontab --}}
                            <div
                                class="rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 p-4">
                                <h4
                                    class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Crontab (Scheduler)
                                </h4>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">Tambahkan baris ini ke
                                    crontab server (<code class="bg-slate-200 dark:bg-slate-600 px-1 rounded">crontab
                                        -e</code>):</p>
                                <pre class="text-xs font-mono bg-slate-900 text-slate-100 p-3 rounded-lg overflow-x-auto select-all">* * * * * cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1</pre>
                            </div>

                            {{-- Queue Worker --}}
                            <div
                                class="rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 p-4">
                                <h4
                                    class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Queue Worker (Supervisor)
                                </h4>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mb-2">Buat file
                                    <code
                                        class="bg-slate-200 dark:bg-slate-600 px-1 rounded">/etc/supervisor/conf.d/tempmail-worker.conf</code>:
                                </p>
                                <pre class="text-xs font-mono bg-slate-900 text-slate-100 p-3 rounded-lg overflow-x-auto select-all">[program:tempmail-worker]
process_name=%(program_name)s_%(process_num)02d
command=php {{ base_path() }}/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile={{ storage_path('logs/worker.log') }}
stopwaitsecs=3600</pre>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Lalu jalankan: <code
                                        class="bg-slate-200 dark:bg-slate-600 px-1 rounded">supervisorctl reread &&
                                        supervisorctl
                                        update</code></p>
                            </div>

                            {{-- Environment Checklist --}}
                            <div
                                class="rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 p-4">
                                <h4
                                    class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-2 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Checklist Production
                                </h4>
                                <ul class="text-xs text-slate-600 dark:text-slate-300 space-y-1.5">
                                    <li class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500 flex-shrink-0"></span>
                                        Set <code
                                            class="bg-slate-200 dark:bg-slate-600 px-1 rounded">APP_ENV=production</code>
                                        dan
                                        <code
                                            class="bg-slate-200 dark:bg-slate-600 px-1 rounded">APP_DEBUG=false</code>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500 flex-shrink-0"></span>
                                        Jalankan <code class="bg-slate-200 dark:bg-slate-600 px-1 rounded">php artisan
                                            config:cache</code>
                                        dan <code class="bg-slate-200 dark:bg-slate-600 px-1 rounded">php artisan
                                            route:cache</code>
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500 flex-shrink-0"></span>
                                        Jalankan <code class="bg-slate-200 dark:bg-slate-600 px-1 rounded">npm run
                                            build</code> untuk
                                        compile assets
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500 flex-shrink-0"></span>
                                        Pastikan <code
                                            class="bg-slate-200 dark:bg-slate-600 px-1 rounded">storage/</code>
                                        writable oleh
                                        web server
                                    </li>
                                    <li class="flex items-center gap-2">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500 flex-shrink-0"></span>
                                        Setup HTTPS (SSL) untuk domain utama
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.login') }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-150 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                        Go to Admin Login
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
