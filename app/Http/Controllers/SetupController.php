<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SetupController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (User::where('is_admin', true)->exists()) {
            return redirect()->route('admin.dashboard');
        }

        return view('setup.wizard');
    }

    public function store(Request $request): RedirectResponse
    {
        if (User::where('is_admin', true)->exists()) {
            return redirect()->route('admin.dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'domain' => ['nullable', 'string', 'max:255'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => true,
        ]);

        Setting::set('contact_email', $validated['email']);

        if (! empty($validated['domain'])) {
            Domain::firstOrCreate(
                ['domain' => $validated['domain']],
                ['is_active' => true],
            );
        }

        return redirect()->route('setup.cloudflare');
    }

    public function cloudflare(): View|RedirectResponse
    {
        if (! User::where('is_admin', true)->exists()) {
            return redirect()->route('setup.index');
        }

        return view('setup.cloudflare');
    }

    public function deployWorker(Request $request): RedirectResponse
    {
        if (! User::where('is_admin', true)->exists()) {
            return redirect()->route('setup.index');
        }

        $validated = $request->validate([
            'cloudflare_api_token' => ['required', 'string', 'max:500'],
            'cloudflare_account_id' => ['required', 'string', 'max:500'],
        ]);

        Setting::set('cloudflare_api_token', $validated['cloudflare_api_token']);
        Setting::set('cloudflare_account_id', $validated['cloudflare_account_id']);

        set_time_limit(300);

        try {
            $exitCode = Artisan::call('cloudflare:setup', [
                '--deploy' => true,
            ]);

            $output = Artisan::output();

            if ($exitCode === 0) {
                return redirect()->route('setup.complete')
                    ->with('worker_deployed', true);
            }

            return redirect()->route('setup.cloudflare')
                ->with('error', 'Deployment failed: '.$output);
        } catch (\Exception $e) {
            return redirect()->route('setup.cloudflare')
                ->with('error', 'Deployment error: '.$e->getMessage());
        }
    }

    public function complete(): View|RedirectResponse
    {
        if (! User::where('is_admin', true)->exists()) {
            return redirect()->route('setup.index');
        }

        return view('setup.complete');
    }
}
