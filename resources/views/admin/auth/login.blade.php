<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ \App\Models\Setting::get('app_name', 'EmailTemp') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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

<body class="h-full font-sans">
    <div class="flex min-h-full">
        {{-- Left branding panel --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center"
            style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4f46e5 100%)">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-20 left-20 w-72 h-72 bg-white rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 right-20 w-96 h-96 bg-indigo-300 rounded-full blur-3xl"></div>
            </div>
            <div class="relative z-10 text-center px-12">
                @if (\App\Models\Setting::get('app_logo_url'))
                    <div class="mb-8 flex justify-center">
                        <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                            alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}"
                            style="height: {{ max(48, (int) \App\Models\Setting::get('app_logo_height', 48)) }}px; width: auto; max-height: 80px;">
                    </div>
                @else
                    <div
                        class="w-20 h-20 mx-auto rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center mb-8 shadow-2xl shadow-indigo-900/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                @endif
                <h2 class="text-3xl font-bold text-white mb-3">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}
                </h2>
                <p class="text-indigo-200 text-lg leading-relaxed max-w-sm mx-auto">Administration panel for managing
                    temporary email services</p>
            </div>
        </div>

        {{-- Right login form --}}
        <div class="flex-1 flex items-center justify-center px-6 py-12 bg-slate-50">
            <div class="w-full max-w-md">
                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8">
                    @if (\App\Models\Setting::get('app_logo_url'))
                        <div class="mb-4 flex justify-center">
                            <img src="{{ \App\Models\Setting::get('app_logo_url') }}"
                                alt="{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}"
                                style="height: {{ (int) \App\Models\Setting::get('app_logo_height', 36) }}px; width: auto; max-height: 60px;">
                        </div>
                    @else
                        <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center mb-4 shadow-lg"
                            style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    @endif
                    <h2 class="text-xl font-bold text-slate-800">{{ \App\Models\Setting::get('app_name', 'EmailTemp') }}
                    </h2>
                </div>

                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/60 p-8">
                    <div class="mb-6">
                        <h1 class="text-2xl font-bold text-slate-800 mb-1">Welcome back</h1>
                        <p class="text-sm text-slate-500">Sign in to your admin account</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 flex items-start gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <ul class="text-sm text-red-800 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email
                                address</label>
                            <input id="email" name="email" type="email" required autofocus
                                value="{{ old('email') }}" placeholder="admin@example.com"
                                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                        </div>
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                            <input id="password" name="password" type="password" required placeholder="••••••••"
                                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                        </div>
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                            <label for="remember" class="ml-2.5 text-sm text-slate-600">Remember me</label>
                        </div>
                        <button type="submit"
                            class="w-full rounded-xl px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-150 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                            Sign in
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
