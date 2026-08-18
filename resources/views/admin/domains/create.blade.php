@extends('admin.layouts.app')
@section('title', 'Add Domain')
@section('content')
    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.domains.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="domain" class="block text-sm font-medium text-slate-700 mb-1.5">Domain</label>
                    <input id="domain" name="domain" type="text" required value="{{ old('domain') }}"
                        placeholder="example.com"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <div>
                    <label for="cloudflare_zone_id" class="block text-sm font-medium text-slate-700 mb-1.5">Cloudflare
                        Zone ID <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input id="cloudflare_zone_id" name="cloudflare_zone_id" type="text"
                        value="{{ old('cloudflare_zone_id') }}"
                        class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 transition-all duration-150 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">
                </div>
                <div class="flex items-center">
                    <input id="is_active" name="is_active" type="checkbox" value="1"
                        {{ old('is_active', true) ? 'checked' : '' }}
                        class="h-4 w-4 rounded-md border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                    <label for="is_active" class="ml-2.5 text-sm text-slate-700">Active</label>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition-all duration-150 hover:shadow-xl hover:-translate-y-0.5"
                        style="background: linear-gradient(135deg, #4f46e5, #6366f1)">
                        Create
                    </button>
                    <a href="{{ route('admin.domains.index') }}"
                        class="rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 border border-slate-300 hover:bg-slate-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
