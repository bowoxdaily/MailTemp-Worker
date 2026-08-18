@extends('admin.layouts.app')
@section('title', 'Email Detail')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.emails.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Emails
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100 space-y-3">
            <h2 class="text-lg font-bold text-slate-800">{{ $email->subject ?: '(No Subject)' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                <div>
                    <span class="text-slate-400">From:</span>
                    <span class="text-slate-700 font-medium ml-1">{{ $email->from_name }}
                        &lt;{{ $email->from_address }}&gt;</span>
                </div>
                <div>
                    <span class="text-slate-400">To:</span>
                    <span class="text-slate-700 font-medium ml-1">{{ $email->temporaryEmail?->email_address }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Received:</span>
                    <span class="text-slate-600 ml-1">{{ $email->received_at->format('d M Y, H:i:s') }}</span>
                </div>
                <div>
                    <span class="text-slate-400">Size:</span>
                    <span class="text-slate-600 ml-1">{{ number_format($email->size_bytes / 1024, 1) }} KB</span>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="px-6 py-5">
            @if ($email->body_html)
                <div class="prose prose-sm max-w-none text-slate-700 border rounded-xl p-4 bg-slate-50 overflow-auto">
                    <iframe srcdoc="{{ e($email->body_html) }}" class="w-full min-h-[400px] border-0"
                        sandbox="allow-same-origin"></iframe>
                </div>
            @else
                <pre class="whitespace-pre-wrap text-sm text-slate-600 bg-slate-50 rounded-xl p-4 border border-slate-200">{{ $email->body_text }}</pre>
            @endif
        </div>

        {{-- Attachments --}}
        @if ($email->attachments && $email->attachments->count())
            <div class="px-6 py-4 border-t border-slate-100">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Attachments ({{ $email->attachments->count() }})</h3>
                <div class="space-y-2">
                    @foreach ($email->attachments as $attachment)
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-200">
                            <svg class="w-5 h-5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <span class="text-sm text-slate-700">{{ $attachment->filename }}</span>
                            <span class="text-xs text-slate-400">{{ number_format($attachment->size_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Actions --}}
    <div class="mt-4 flex gap-2">
        <form method="POST" action="{{ route('admin.emails.destroy', $email) }}"
            onsubmit="return confirm('Delete this email permanently?')">
            @csrf @method('DELETE')
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Delete Email
            </button>
        </form>
    </div>
@endsection
