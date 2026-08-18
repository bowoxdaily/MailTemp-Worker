@extends('admin.layouts.app')
@section('title', 'Email Monitor')
@section('content')
    <div class="mb-6">
        <form method="GET" action="{{ route('admin.emails.index') }}" class="flex gap-2 max-w-lg">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input name="search" type="text" value="{{ request('search') }}"
                    placeholder="Search by sender, subject, or recipient..."
                    class="block w-full rounded-xl border border-slate-300 bg-white pl-10 pr-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
            </div>
            <button type="submit"
                class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white transition-all duration-150 hover:shadow-lg"
                style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                Search
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            To</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            From</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Subject</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Size</th>
                        <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Received</th>
                        <th class="px-3 py-3 pr-6 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($emails as $email)
                        <tr class="hover:bg-slate-50/50 transition-colors duration-100">
                            <td class="whitespace-nowrap py-3.5 pl-6 pr-3 text-sm font-medium text-slate-800">
                                {{ $email->temporaryEmail?->email_address }}</td>
                            <td class="whitespace-nowrap px-3 py-3.5 text-sm text-slate-500">
                                {{ $email->from_address }}</td>
                            <td class="px-3 py-3.5 text-sm text-slate-500 max-w-xs truncate">{{ $email->subject }}</td>
                            <td class="whitespace-nowrap px-3 py-3.5 text-sm text-slate-400">
                                {{ number_format($email->size_bytes / 1024, 1) }} KB</td>
                            <td class="whitespace-nowrap px-3 py-3.5 text-sm text-slate-400">
                                {{ $email->received_at->diffForHumans() }}</td>
                            <td class="whitespace-nowrap px-3 py-3.5 pr-6 text-sm text-right">
                                <a href="{{ route('admin.emails.show', $email) }}"
                                    class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="text-sm text-slate-400">No emails found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $emails->links() }}</div>
@endsection
