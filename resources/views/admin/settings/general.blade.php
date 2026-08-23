@extends('admin.layouts.app')

@section('title', 'General Settings')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="_redirect" value="{{ route('admin.settings.index') }}">

        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-800">Mail & Limit Configuration</h2>
                <p class="text-sm text-slate-500 mt-0.5">Atur batas rate limit, ukuran email, ukuran attachment, dan polling
                    frontend.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @php
                    $generalKeys = [
                        'default_expiry_minutes' => 'Default Temporary Email Expiry (Minutes)',
                        'rate_limit_generate' => 'Max Email Generates per IP per Hour',
                        'max_email_size_bytes' => 'Max Incoming Email Size (Bytes)',
                        'max_attachment_size_bytes' => 'Max Attachment Size (Bytes)',
                        'polling_interval_seconds' => 'Frontend Inbox Polling Interval (Seconds)',
                        'cleanup_interval_minutes' => 'Auto Cleanup Interval (Minutes)',
                    ];
                @endphp

                @foreach ($generalKeys as $key => $label)
                    @php $setting = $settings[$key] ?? null; @endphp
                    <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                        <div class="sm:w-1/3">
                            <label for="setting-{{ $key }}" class="text-sm font-medium text-slate-700">
                                {{ $label }}
                            </label>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $key }}</p>
                        </div>
                        <div class="sm:flex-1">
                            <input type="text" name="settings[{{ $key }}]" id="setting-{{ $key }}"
                                value="{{ $setting?->value }}"
                                class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @if ($setting?->description)
                                <p class="mt-1 text-xs text-slate-400">{{ $setting->description }}</p>
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
@endsection
