@extends('admin.layouts.app')
@section('title', 'Abuse Management')
@section('content')
    <div x-data="abuseManager()" x-cloak>
        {{-- Toast --}}
        <div x-show="toast" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed top-4 right-4 z-[60] flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 shadow-lg">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium text-emerald-800" x-text="toast"></p>
        </div>

        {{-- Error Toast --}}
        <div x-show="errorToast" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="fixed top-4 right-4 z-[60] flex items-center gap-3 rounded-xl bg-red-50 border border-red-200 px-4 py-3 shadow-lg">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm font-medium text-red-800" x-text="errorToast"></p>
        </div>

        {{-- Blocked Senders --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-800">Blocked Senders</h2>
            </div>
            <div class="px-6 py-4 border-b border-slate-100">
                <form @submit.prevent="blockSender()" class="flex gap-2 max-w-lg">
                    <input x-model="senderForm.email_address" type="email" required placeholder="spam@example.com"
                        class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                    <input x-model="senderForm.reason" type="text" placeholder="Reason (optional)"
                        class="w-40 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                    <button type="submit" :disabled="loading"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 hover:shadow-lg disabled:opacity-50"
                        style="background: linear-gradient(135deg, #ef4444, #f87171)">
                        Block
                    </button>
                </form>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($blockedSenders as $sender)
                    <div class="flex items-center justify-between px-6 py-3" id="sender-{{ $sender->id }}">
                        <div>
                            <span class="text-sm text-slate-700">{{ $sender->email_address }}</span>
                            @if ($sender->reason)
                                <span class="text-xs text-slate-400 ml-2">— {{ $sender->reason }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400">{{ $sender->created_at->diffForHumans() }}</span>
                            <button @click="openUnblock('sender', {{ $sender->id }}, '{{ $sender->email_address }}')"
                                title="Unblock"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-slate-400">No blocked senders</p>
                    </div>
                @endforelse
            </div>
            @if ($blockedSenders->hasPages())
                <div class="px-6 py-3 border-t border-slate-100">{{ $blockedSenders->links() }}</div>
            @endif
        </div>

        {{-- Blocked Domains --}}
        <div class="mt-8 bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-semibold text-slate-800">Blocked Sender Domains</h2>
            </div>
            <div class="px-6 py-4 border-b border-slate-100">
                <form @submit.prevent="blockDomain()" class="flex gap-2 max-w-lg">
                    <input x-model="domainForm.domain" type="text" required placeholder="spam-domain.com"
                        class="flex-1 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                    <input x-model="domainForm.reason" type="text" placeholder="Reason (optional)"
                        class="w-40 rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                    <button type="submit" :disabled="loading"
                        class="rounded-xl px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 hover:shadow-lg disabled:opacity-50"
                        style="background: linear-gradient(135deg, #ef4444, #f87171)">
                        Block
                    </button>
                </form>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse ($blockedDomains as $domain)
                    <div class="flex items-center justify-between px-6 py-3" id="domain-{{ $domain->id }}">
                        <div>
                            <span class="text-sm text-slate-700">{{ $domain->domain }}</span>
                            @if ($domain->reason)
                                <span class="text-xs text-slate-400 ml-2">— {{ $domain->reason }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-slate-400">{{ $domain->created_at->diffForHumans() }}</span>
                            <button @click="openUnblock('domain', {{ $domain->id }}, '{{ $domain->domain }}')"
                                title="Unblock"
                                class="p-1.5 rounded-lg text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-sm text-slate-400">No blocked domains</p>
                    </div>
                @endforelse
            </div>
            @if ($blockedDomains->hasPages())
                <div class="px-6 py-3 border-t border-slate-100">{{ $blockedDomains->links() }}</div>
            @endif
        </div>

        {{-- Unblock Confirm Modal --}}
        <template x-teleport="body">
            <div x-show="showUnblockModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @keydown.escape.window="showUnblockModal = false">
                <div x-show="showUnblockModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50"
                    @click="showUnblockModal = false"></div>
                <div x-show="showUnblockModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-1">Unblock</h3>
                    <p class="text-sm text-slate-500 mb-6">Unblock <strong x-text="unblockName"></strong>?</p>
                    <div class="flex justify-center gap-3">
                        <button @click="showUnblockModal = false"
                            class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="confirmUnblock()" :disabled="loading"
                            class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all disabled:opacity-50"
                            x-text="loading ? 'Unblocking…' : 'Unblock'">
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function abuseManager() {
            return {
                loading: false,
                toast: '',
                errorToast: '',
                showUnblockModal: false,
                unblockType: '',
                unblockId: null,
                unblockName: '',
                senderForm: {
                    email_address: '',
                    reason: ''
                },
                domainForm: {
                    domain: '',
                    reason: ''
                },
                csrf: document.querySelector('meta[name="csrf-token"]').content,

                async blockSender() {
                    this.loading = true;
                    try {
                        const res = await fetch('/admin/abuse/block-sender', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            body: JSON.stringify(this.senderForm),
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.showError(data.errors ? Object.values(data.errors).flat()[0] : data.message);
                            this.loading = false;
                            return;
                        }
                        this.senderForm = {
                            email_address: '',
                            reason: ''
                        };
                        this.showToast(data.message);
                        setTimeout(() => location.reload(), 600);
                    } catch (e) {
                        this.showError('Network error.');
                    }
                    this.loading = false;
                },

                async blockDomain() {
                    this.loading = true;
                    try {
                        const res = await fetch('/admin/abuse/block-domain', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            body: JSON.stringify(this.domainForm),
                        });
                        const data = await res.json();
                        if (!res.ok) {
                            this.showError(data.errors ? Object.values(data.errors).flat()[0] : data.message);
                            this.loading = false;
                            return;
                        }
                        this.domainForm = {
                            domain: '',
                            reason: ''
                        };
                        this.showToast(data.message);
                        setTimeout(() => location.reload(), 600);
                    } catch (e) {
                        this.showError('Network error.');
                    }
                    this.loading = false;
                },

                openUnblock(type, id, name) {
                    this.unblockType = type;
                    this.unblockId = id;
                    this.unblockName = name;
                    this.showUnblockModal = true;
                },

                async confirmUnblock() {
                    this.loading = true;
                    const url = this.unblockType === 'sender' ?
                        `/admin/abuse/unblock-sender/${this.unblockId}` :
                        `/admin/abuse/unblock-domain/${this.unblockId}`;
                    try {
                        const res = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                        });
                        const data = await res.json();
                        this.showUnblockModal = false;
                        this.showToast(data.message);
                        setTimeout(() => location.reload(), 600);
                    } catch (e) {
                        this.showError('Failed to unblock.');
                    }
                    this.loading = false;
                },

                showToast(msg) {
                    this.toast = msg;
                    setTimeout(() => this.toast = '', 3000);
                },

                showError(msg) {
                    this.errorToast = msg;
                    setTimeout(() => this.errorToast = '', 4000);
                },
            };
        }
    </script>
@endsection
