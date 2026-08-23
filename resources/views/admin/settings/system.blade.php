@extends('admin.layouts.app')

@section('title', 'System & Scheduler')

@section('content')
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="_redirect" value="{{ route('admin.settings.system') }}">

        {{-- Scheduler Health Card --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">Cron Scheduler Health</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Status cron job Laravel untuk auto-cleanup inbox dan email
                        kadaluarsa.</p>
                </div>
                <div>
                    @if ($schedulerStatus['is_running'])
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Running / Healthy
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Inactive / Not Detected
                        </span>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Last Scheduler Run:</span>
                    <span class="font-medium text-slate-800">
                        {{ $schedulerStatus['last_run'] ? $schedulerStatus['last_run']->diffForHumans() . ' (' . $schedulerStatus['last_run']->toDateTimeString() . ')' : 'Never' }}
                    </span>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-medium mb-1.5">Server Cron Entry Required:</p>
                    <div class="p-3 bg-slate-900 text-slate-100 rounded-xl font-mono text-xs overflow-x-auto select-all">
                        {{ $schedulerStatus['cron_command'] }}
                    </div>
                </div>
            </div>
        </div>

        {{-- System Settings --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-800">Scheduler & Cleanup Configuration</h2>
                <p class="text-sm text-slate-500 mt-0.5">Interval pembersihan otomatis database dan file attachment.</p>
            </div>

            <div class="divide-y divide-slate-100">
                @php
                    $cleanupSetting = $settings['cleanup_interval_minutes'] ?? null;
                @endphp
                <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-6">
                    <div class="sm:w-1/3">
                        <label for="setting-cleanup_interval_minutes" class="text-sm font-medium text-slate-700">
                            Cleanup Interval (Minutes)
                        </label>
                        <p class="text-xs text-slate-400 font-mono mt-0.5">cleanup_interval_minutes</p>
                    </div>
                    <div class="sm:flex-1">
                        <input type="number" name="settings[cleanup_interval_minutes]"
                            id="setting-cleanup_interval_minutes" value="{{ $cleanupSetting?->value ?? 10 }}"
                            class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="mt-1 text-xs text-slate-400">Jarak waktu eksekusi command <code>emails:cleanup</code>.</p>
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
                Save System Settings
            </button>
        </div>
    </form>
@endsection
