<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DomainController extends Controller
{
    public function index(): View
    {
        $domains = Domain::withCount('temporaryEmails')
            ->latest()
            ->paginate(20);

        return view('admin.domains.index', compact('domains'));
    }

    public function create(): View
    {
        return view('admin.domains.create');
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'unique:domains'],
            'cloudflare_zone_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $domain = Domain::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Domain created.', 'domain' => $domain]);
        }

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain created.');
    }

    public function edit(Domain $domain): View
    {
        return view('admin.domains.edit', compact('domain'));
    }

    public function update(Request $request, Domain $domain): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'unique:domains,domain,'.$domain->id],
            'cloudflare_zone_id' => ['nullable', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $domain->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Domain updated.', 'domain' => $domain]);
        }

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain updated.');
    }

    public function destroy(Request $request, Domain $domain): JsonResponse|RedirectResponse
    {
        $domain->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Domain deleted.']);
        }

        return redirect()->route('admin.domains.index')
            ->with('success', 'Domain deleted.');
    }

    public function toggle(Request $request, Domain $domain): JsonResponse|RedirectResponse
    {
        $domain->update(['is_active' => ! $domain->is_active]);

        $message = $domain->is_active ? 'Domain activated.' : 'Domain deactivated.';

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'domain' => $domain]);
        }

        return redirect()->route('admin.domains.index')
            ->with('success', $message);
    }
}
