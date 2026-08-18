@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total Emails --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Emails</p>
                <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($totalEmails) }}</p>
            </div>
        </div>

        {{-- Emails Today --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-violet-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Emails Today</p>
                <p class="text-2xl font-bold text-violet-600 mt-0.5">{{ number_format($todayEmails) }}</p>
            </div>
        </div>

        {{-- Active Temp Emails --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Active Temp Emails</p>
                <p class="text-2xl font-bold text-emerald-600 mt-0.5">{{ number_format($activeTemporaryEmails) }}</p>
            </div>
        </div>

        {{-- Expired Temp Emails --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-slate-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Expired Temp Emails</p>
                <p class="text-2xl font-bold text-slate-400 mt-0.5">{{ number_format($expiredTemporaryEmails) }}</p>
            </div>
        </div>

        {{-- Active Domains --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Active Domains</p>
                <p class="text-2xl font-bold text-slate-800 mt-0.5">{{ number_format($activeDomains) }}</p>
            </div>
        </div>

        {{-- Blocked Senders --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Blocked Senders</p>
                <p class="text-2xl font-bold text-red-500 mt-0.5">{{ number_format($blockedSenders) }}</p>
            </div>
        </div>

        {{-- Blocked Domains --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-5 flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl bg-orange-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Blocked Domains</p>
                <p class="text-2xl font-bold text-orange-500 mt-0.5">{{ number_format($blockedDomains) }}</p>
            </div>
        </div>
    </div>

    {{-- Recent Emails --}}
    <div class="mt-8">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-semibold text-slate-800">Recent Emails</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                To</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                From</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Subject</th>
                            <th
                                class="px-3 py-3 pr-6 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Received</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentEmails as $email)
                            <tr class="hover:bg-slate-50/50 transition-colors duration-100">
                                <td class="whitespace-nowrap py-3.5 pl-6 pr-3 text-sm font-medium text-slate-800">
                                    {{ $email->temporaryEmail?->email_address }}</td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm text-slate-500">
                                    {{ $email->from_address }}</td>
                                <td class="px-3 py-3.5 text-sm text-slate-500 max-w-xs truncate">{{ $email->subject }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 pr-6 text-sm text-slate-400">
                                    {{ $email->received_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm text-slate-400">No emails received yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
