@extends('admin.layouts.app')

@section('title', 'Cloudflare & Worker')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="_redirect" value="{{ route('admin.settings.cloudflare') }}">

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-800">Cloudflare API & Worker Credentials</h2>
                <p class="text-sm text-slate-500 mt-0.5">Kredensial untuk autentikasi worker webhook dan otomatisasi
                    deployment menggunakan Wrangler.</p>
            </div>

            <div class="divide-y divide-slate-100">
                {{-- Worker Secret --}}
                @php $workerSecret = $settings['cloudflare_worker_secret'] ?? null; @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div class="sm:w-1/3">
                        <label for="setting-cloudflare_worker_secret" class="text-sm font-medium text-slate-700">
                            Cloudflare Worker Secret
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">cloudflare_worker_secret</p>
                    </div>
                    <div class="sm:flex-1">
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="settings[cloudflare_worker_secret]"
                                id="setting-cloudflare_worker_secret" value="{{ $workerSecret?->value }}"
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
                        <p class="mt-1 text-xs text-slate-400">Secret HMAC SHA-256 untuk memvalidasi kiriman webhook dari
                            Cloudflare Worker.</p>
                    </div>
                </div>

                {{-- API Token --}}
                @php $apiToken = $settings['cloudflare_api_token'] ?? null; @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div class="sm:w-1/3">
                        <label for="setting-cloudflare_api_token" class="text-sm font-medium text-slate-700">
                            Cloudflare API Token
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">cloudflare_api_token</p>
                    </div>
                    <div class="sm:flex-1">
                        <div x-data="{ show: false }" class="relative">
                            <input :type="show ? 'text' : 'password'" name="settings[cloudflare_api_token]"
                                id="setting-cloudflare_api_token" value="{{ $apiToken?->value }}"
                                placeholder="Opsional (untuk auto deploy via Wrangler)"
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
                        <p class="mt-1 text-xs text-slate-400">Token Cloudflare dengan izin Workers Scripts & Secrets.</p>
                    </div>
                </div>

                {{-- Account ID --}}
                @php $accountId = $settings['cloudflare_account_id'] ?? null; @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div class="sm:w-1/3">
                        <label for="setting-cloudflare_account_id" class="text-sm font-medium text-slate-700">
                            Cloudflare Account ID
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">cloudflare_account_id</p>
                    </div>
                    <div class="sm:flex-1">
                        <input type="text" name="settings[cloudflare_account_id]" id="setting-cloudflare_account_id"
                            value="{{ $accountId?->value }}" placeholder="32 karakter Cloudflare Account ID"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-slate-400">Dapat ditemukan di dashboard Cloudflare bagian URL / sidebar
                            kanan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save Cloudflare Settings
            </button>
        </div>
    </form>

    {{-- Deployment Box --}}
    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Cloudflare Worker Deployment</h2>
            <p class="text-sm text-slate-500 mt-0.5">Deploy or update the Cloudflare email receiver worker using Wrangler.
            </p>
        </div>
        <div class="px-6 py-4 space-y-4">
            <p class="text-sm text-slate-600 leading-relaxed">
                Menjalankan perintah deployment otomatis via Wrangler. Mengunggah konfigurasi <code>.dev.vars</code>,
                <code>BACKEND_URL</code>, <code>WORKER_SECRET</code> dan script worker
                <code>cloudflare/email-worker/index.js</code> ke Cloudflare.
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
