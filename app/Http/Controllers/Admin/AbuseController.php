<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDomain;
use App\Models\BlockedSender;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbuseController extends Controller
{
    public function index(): View
    {
        $blockedSenders = BlockedSender::latest()->paginate(20, ['*'], 'senders_page');
        $blockedDomains = BlockedDomain::latest()->paginate(20, ['*'], 'domains_page');

        return view('admin.abuse.index', compact('blockedSenders', 'blockedDomains'));
    }

    public function blockSender(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'email_address' => ['required', 'email', 'max:255', 'unique:blocked_senders'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $sender = BlockedSender::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Sender blocked.', 'sender' => $sender]);
        }

        return redirect()->route('admin.abuse.index')
            ->with('success', 'Sender blocked.');
    }

    public function unblockSender(Request $request, BlockedSender $blockedSender): JsonResponse|RedirectResponse
    {
        $blockedSender->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Sender unblocked.']);
        }

        return redirect()->route('admin.abuse.index')
            ->with('success', 'Sender unblocked.');
    }

    public function blockDomain(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'domain' => ['required', 'string', 'max:255', 'unique:blocked_domains'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $domain = BlockedDomain::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Domain blocked.', 'domain' => $domain]);
        }

        return redirect()->route('admin.abuse.index')
            ->with('success', 'Domain blocked.');
    }

    public function unblockDomain(Request $request, BlockedDomain $blockedDomain): JsonResponse|RedirectResponse
    {
        $blockedDomain->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Domain unblocked.']);
        }

        return redirect()->route('admin.abuse.index')
            ->with('success', 'Domain unblocked.');
    }
}
