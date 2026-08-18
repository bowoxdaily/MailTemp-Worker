@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    @if (session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
            {!! session('success') !!}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-medium">
            {!! session('error') !!}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-800">Application Settings</h2>
                <p class="text-sm text-slate-500 mt-0.5">Configure dynamic application settings stored in database.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @foreach ($settings as $key => $setting)
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                        <div class="sm:w-1/3">
                            <label for="setting-{{ $key }}" class="text-sm font-medium text-slate-700">
                                {{ $setting->description ?? $key }}
                            </label>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $key }}</p>
                        </div>
                        <div class="sm:flex-1">
                            @if (in_array($key, ['cloudflare_worker_secret', 'cloudflare_api_token']))
                                <div x-data="{ show: false }" class="relative">
                                    <input :type="show ? 'text' : 'password'" name="settings[{{ $key }}]"
                                        id="setting-{{ $key }}" value="{{ $setting->value }}"
                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 pr-10">
                                    <button type="button" @click="show = !show"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            @else
                                <input type="text" name="settings[{{ $key }}]" id="setting-{{ $key }}"
                                    value="{{ $setting->value }}"
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Settings
            </button>
        </div>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Cloudflare Worker Deployment</h2>
            <p class="text-sm text-slate-500 mt-0.5">Deploy or update the Cloudflare email receiver worker using Wrangler.
            </p>
        </div>
        <div class="px-6 py-4 space-y-4">
            <p class="text-sm text-slate-600 leading-relaxed">
                Clicking the button below will automatically generate the local <code>.dev.vars</code> file, set the
                <code>BACKEND_URL</code> and <code>WORKER_SECRET</code> secrets on Cloudflare, and deploy the worker script
                dynamically using Wrangler.
            </p>
            <form method="POST" action="{{ route('admin.settings.cloudflare-deploy') }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 transition-colors">
                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z" />
                    </svg>
                    Deploy Cloudflare Worker
                </button>
            </form>
        </div>
    </div>
@endsection
