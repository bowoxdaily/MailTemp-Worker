<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedDomain;
use App\Models\BlockedSender;
use App\Models\Domain;
use App\Models\Email;
use App\Models\TemporaryEmail;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalEmails = Email::count();
        $todayEmails = Email::whereDate('received_at', today())->count();
        $activeTemporaryEmails = TemporaryEmail::where('expires_at', '>', now())->count();
        $expiredTemporaryEmails = TemporaryEmail::where('expires_at', '<=', now())->count();
        $activeDomains = Domain::where('is_active', true)->count();
        $blockedSenders = BlockedSender::count();
        $blockedDomains = BlockedDomain::count();
        $recentEmails = Email::with('temporaryEmail')
            ->latest('received_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalEmails',
            'todayEmails',
            'activeTemporaryEmails',
            'expiredTemporaryEmails',
            'activeDomains',
            'blockedSenders',
            'blockedDomains',
            'recentEmails',
        ));
    }
}
