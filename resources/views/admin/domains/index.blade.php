@extends('admin.layouts.app')
@section('title', 'Domains')
@section('content')
    <div x-data="domainManager()" x-cloak>
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

        {{-- Header --}}
        <div class="mb-6 flex justify-end">
            <button @click="openCreate()"
                class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all duration-150 hover:shadow-xl hover:-translate-y-0.5"
                style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Domain
            </button>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th
                                class="py-3 pl-6 pr-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Domain</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Temp Emails</th>
                            <th class="px-3 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Cloudflare Zone</th>
                            <th
                                class="px-3 py-3 pr-6 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($domains as $domain)
                            <tr class="hover:bg-slate-50/50 transition-colors duration-100"
                                id="domain-row-{{ $domain->id }}">
                                <td class="whitespace-nowrap py-3.5 pl-6 pr-3 text-sm font-medium text-slate-800">
                                    {{ $domain->domain }}</td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm">
                                    @if ($domain->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm text-slate-500">
                                    {{ $domain->temporary_emails_count }}</td>
                                <td class="whitespace-nowrap px-3 py-3.5 text-sm text-slate-400 font-mono text-xs">
                                    {{ $domain->cloudflare_zone_id ?? '—' }}</td>
                                <td class="whitespace-nowrap px-3 py-3.5 pr-6 text-sm text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button
                                            @click="toggleDomain({{ $domain->id }}, '{{ $domain->is_active ? 'Deactivate' : 'Activate' }}')"
                                            title="{{ $domain->is_active ? 'Deactivate' : 'Activate' }}"
                                            class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </button>
                                        <button
                                            @click="openEdit({{ $domain->id }}, '{{ $domain->domain }}', '{{ $domain->cloudflare_zone_id }}', {{ $domain->is_active ? 'true' : 'false' }})"
                                            title="Edit"
                                            class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button @click="openDelete({{ $domain->id }}, '{{ $domain->domain }}')"
                                            title="Delete"
                                            class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9" />
                                    </svg>
                                    <p class="text-sm text-slate-400">No domains configured yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">{{ $domains->links() }}</div>

        {{-- Create/Edit Modal --}}
        <template x-teleport="body">
            <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @keydown.escape.window="showModal = false">
                <div x-show="showModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50" @click="showModal = false">
                </div>
                <div x-show="showModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-md bg-white rounded-2xl shadow-xl">
                    <div class="px-6 py-5 border-b border-slate-200/60">
                        <h3 class="text-lg font-semibold text-slate-800" x-text="editId ? 'Edit Domain' : 'Add Domain'">
                        </h3>
                    </div>
                    <form @submit.prevent="submitForm()">
                        <div class="px-6 py-5 space-y-4">
                            {{-- Errors --}}
                            <div x-show="Object.keys(errors).length > 0"
                                class="rounded-xl bg-red-50 border border-red-200 px-4 py-3">
                                <template x-for="(msgs, field) in errors" :key="field">
                                    <template x-for="msg in msgs" :key="msg">
                                        <p class="text-sm text-red-800" x-text="msg"></p>
                                    </template>
                                </template>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Domain</label>
                                <input x-model="form.domain" type="text" required placeholder="example.com"
                                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">Cloudflare Zone ID
                                    <span class="text-slate-400 font-normal">(optional)</span></label>
                                <input x-model="form.cloudflare_zone_id" type="text"
                                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                            </div>
                            <div class="flex items-center">
                                <input x-model="form.is_active" type="checkbox"
                                    class="h-4 w-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                                <label class="ml-2.5 text-sm text-slate-700">Active</label>
                            </div>
                        </div>
                        <div class="px-6 py-4 border-t border-slate-200/60 flex justify-end gap-3">
                            <button type="button" @click="showModal = false"
                                class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" :disabled="loading"
                                class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all duration-150 hover:shadow-xl disabled:opacity-50"
                                style="background: linear-gradient(135deg, #4f46e5, #6366f1)"
                                x-text="loading ? 'Saving…' : (editId ? 'Update' : 'Create')">
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        {{-- Delete Confirm Modal --}}
        <template x-teleport="body">
            <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @keydown.escape.window="showDeleteModal = false">
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="absolute inset-0 bg-black/50"
                    @click="showDeleteModal = false"></div>
                <div x-show="showDeleteModal" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl p-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-1">Delete Domain</h3>
                    <p class="text-sm text-slate-500 mb-6">Delete <strong x-text="deleteName"></strong>? This cannot
                        be undone.</p>
                    <div class="flex justify-center gap-3">
                        <button @click="showDeleteModal = false"
                            class="rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button @click="confirmDelete()" :disabled="loading"
                            class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-500/20 transition-all disabled:opacity-50"
                            x-text="loading ? 'Deleting…' : 'Delete'">
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <script>
        function domainManager() {
            return {
                showModal: false,
                showDeleteModal: false,
                loading: false,
                toast: '',
                editId: null,
                deleteId: null,
                deleteName: '',
                errors: {},
                form: {
                    domain: '',
                    cloudflare_zone_id: '',
                    is_active: true,
                },
                csrf: document.querySelector('meta[name="csrf-token"]').content,

                openCreate() {
                    this.editId = null;
                    this.errors = {};
                    this.form = {
                        domain: '',
                        cloudflare_zone_id: '',
                        is_active: true
                    };
                    this.showModal = true;
                },

                openEdit(id, domain, zoneId, active) {
                    this.editId = id;
                    this.errors = {};
                    this.form = {
                        domain: domain,
                        cloudflare_zone_id: zoneId || '',
                        is_active: active,
                    };
                    this.showModal = true;
                },

                openDelete(id, name) {
                    this.deleteId = id;
                    this.deleteName = name;
                    this.showDeleteModal = true;
                },

                async submitForm() {
                    this.loading = true;
                    this.errors = {};
                    const url = this.editId ?
                        `/admin/domains/${this.editId}` :
                        '/admin/domains';
                    const method = this.editId ? 'PUT' : 'POST';

                    const body = {
                        ...this.form,
                        is_active: this.form.is_active ? '1' : '0',
                    };

                    try {
                        const res = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                            body: JSON.stringify(body),
                        });
                        const data = await res.json();

                        if (!res.ok) {
                            if (res.status === 422 && data.errors) {
                                this.errors = data.errors;
                            } else {
                                this.errors = {
                                    _: [data.message || 'Something went wrong.']
                                };
                            }
                            this.loading = false;
                            return;
                        }

                        this.showModal = false;
                        this.showToast(data.message);
                        setTimeout(() => location.reload(), 600);
                    } catch (e) {
                        this.errors = {
                            _: ['Network error. Please try again.']
                        };
                    }
                    this.loading = false;
                },

                async toggleDomain(id) {
                    try {
                        const res = await fetch(`/admin/domains/${id}/toggle`, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                        });
                        const data = await res.json();
                        this.showToast(data.message);
                        setTimeout(() => location.reload(), 600);
                    } catch (e) {
                        this.showToast('Failed to toggle domain.');
                    }
                },

                async confirmDelete() {
                    this.loading = true;
                    try {
                        const res = await fetch(`/admin/domains/${this.deleteId}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': this.csrf,
                            },
                        });
                        const data = await res.json();
                        this.showDeleteModal = false;
                        this.showToast(data.message);
                        setTimeout(() => location.reload(), 600);
                    } catch (e) {
                        this.showToast('Failed to delete domain.');
                    }
                    this.loading = false;
                },

                showToast(msg) {
                    this.toast = msg;
                    setTimeout(() => this.toast = '', 3000);
                },
            };
        }
    </script>
@endsection
