@extends('admin.layouts.app')

@section('title', 'Branding Settings')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="_redirect" value="{{ route('admin.settings.branding') }}">

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-800">Brand Identity & Logo</h2>
                <p class="text-sm text-slate-500 mt-0.5">Konfigurasi nama website, file logo di storage, ukuran tinggi logo,
                    dan teks copyright footer.</p>
            </div>

            <div class="divide-y divide-slate-100">
                {{-- App Name --}}
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div class="sm:w-1/3">
                        <label for="setting-app_name" class="text-sm font-medium text-slate-700">
                            Application Name
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">app_name</p>
                    </div>
                    <div class="sm:flex-1">
                        <input type="text" name="settings[app_name]" id="setting-app_name"
                            value="{{ $settings['app_name']->value ?? 'EmailTemp' }}" placeholder="EmailTemp"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- App Logo Upload & Storage --}}
                @php
                    $currentLogoUrl = $settings['app_logo_url']->value ?? null;
                    $currentHeight = (int) ($settings['app_logo_height']->value ?? 32);
                @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6" x-data="{
                    previewUrl: '{{ $currentLogoUrl ? $currentLogoUrl : '' }}',
                    removeLogo: false,
                    heightPx: {{ $currentHeight }},
                    handleFileChange(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                            this.removeLogo = false;
                        }
                    }
                }">
                    <div class="sm:w-1/3">
                        <label class="text-sm font-medium text-slate-700">
                            Website Logo
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">app_logo (storage/branding)</p>
                        <p class="text-xs text-slate-400 mt-1">Format: PNG, JPG, SVG, WebP. Maksimal 2MB.</p>
                    </div>
                    <div class="sm:flex-1 space-y-4">
                        {{-- Live Preview Box --}}
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex flex-col gap-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">Preview Logo
                                Aktif:</span>
                            <div
                                class="p-4 rounded-lg bg-slate-900/5 dark:bg-slate-900 border border-dashed border-slate-300 flex items-center justify-center min-h-[80px]">
                                <template x-if="previewUrl && !removeLogo">
                                    <img :src="previewUrl" alt="Logo Preview"
                                        :style="`height: ${heightPx}px; width: auto; max-width: 100%;`"
                                        class="object-contain transition-all duration-150">
                                </template>
                                <template x-if="!previewUrl || removeLogo">
                                    <div class="flex items-center gap-2 text-slate-400 text-sm">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>(Default icon + Nama text)</span>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- File Input & Actions --}}
                        <div class="flex flex-wrap items-center gap-3">
                            <label
                                class="cursor-pointer inline-flex items-center gap-2 px-3.5 py-2 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 shadow-sm transition">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                <span>Pilih Gambar Logo</span>
                                <input type="file" name="app_logo"
                                    accept="image/png,image/jpeg,image/svg+xml,image/webp,image/gif" class="hidden"
                                    @change="handleFileChange($event)">
                            </label>

                            <template x-if="previewUrl && !removeLogo">
                                <button type="button" @click="removeLogo = true; $refs.removeLogoInput.value = '1'"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Hapus Logo
                                </button>
                            </template>

                            <input type="hidden" name="remove_logo" x-ref="removeLogoInput"
                                :value="removeLogo ? '1' : '0'">
                        </div>

                        {{-- Logo Height Controls --}}
                        <div class="pt-3 border-t border-slate-200/80">
                            <div class="flex items-center justify-between mb-1.5">
                                <label for="setting-app_logo_height" class="text-xs font-semibold text-slate-700">
                                    Tinggi Logo (Height): <span x-text="heightPx + 'px'"
                                        class="text-indigo-600 font-mono"></span>
                                </label>
                                <span class="text-[11px] text-slate-400">Min: 16px | Max: 100px</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <input type="range" min="16" max="100" step="2" x-model="heightPx"
                                    class="w-full h-2 bg-slate-200 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                <input type="number" name="settings[app_logo_height]" id="setting-app_logo_height"
                                    min="16" max="100" x-model="heightPx"
                                    class="w-20 rounded-lg border border-slate-300 px-2.5 py-1 text-xs text-center font-mono font-bold text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Ukuran tinggi logo di header, navbar, dan footer
                                aplikasi.</p>
                        </div>
                    </div>
                </div>

                {{-- Favicon Upload & Storage --}}
                @php
                    $currentFaviconUrl = $settings['favicon_url']->value ?? null;
                @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6" x-data="{
                    previewUrl: '{{ $currentFaviconUrl ? $currentFaviconUrl : asset('favicon.svg') }}',
                    removeFavicon: false,
                    handleFileChange(event) {
                        const file = event.target.files[0];
                        if (file) {
                            this.previewUrl = URL.createObjectURL(file);
                            this.removeFavicon = false;
                        }
                    }
                }">
                    <div class="sm:w-1/3">
                        <label class="text-sm font-medium text-slate-700">Website Icon</label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">favicon (storage/branding)</p>
                        <p class="text-xs text-slate-400 mt-1">Format: ICO, PNG, SVG. Maksimal 1MB.</p>
                    </div>
                    <div class="sm:flex-1 space-y-3">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 flex items-center gap-4">
                            <img :src="previewUrl" alt="Icon Preview" class="w-12 h-12 object-contain rounded-lg">
                            <span class="text-xs text-slate-500">Icon browser dan bookmark.</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <label class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold cursor-pointer hover:bg-indigo-100 transition">
                                <span>Pilih Icon</span>
                                <input type="file" name="favicon" accept="image/x-icon,image/png,image/svg+xml" class="hidden"
                                    @change="handleFileChange($event)">
                            </label>
                            <template x-if="previewUrl && !removeFavicon && '{{ $currentFaviconUrl }}'">
                                <button type="button" @click="removeFavicon = true; $refs.removeFaviconInput.value = '1'"
                                    class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-rose-200 transition">
                                    Hapus Icon
                                </button>
                            </template>
                            <input type="hidden" name="remove_favicon" x-ref="removeFaviconInput"
                                :value="removeFavicon ? '1' : '0'">
                        </div>
                    </div>
                </div>

                {{-- Footer Copyright --}}
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div class="sm:w-1/3">
                        <label for="setting-footer_copyright" class="text-sm font-medium text-slate-700">
                            Footer Copyright Text
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">footer_copyright</p>
                    </div>
                    <div class="sm:flex-1">
                        <input type="text" name="settings[footer_copyright]" id="setting-footer_copyright"
                            value="{{ $settings['footer_copyright']->value ?? '' }}"
                            placeholder="© {{ date('Y') }} EmailTemp. All rights reserved."
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
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
                Save Branding
            </button>
        </div>
    </form>
@endsection
