<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:500',
        ]);

        foreach ($request->input('settings') as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated.');
    }

    public function deployCloudflare(): RedirectResponse
    {
        try {
            $exitCode = Artisan::call('cloudflare:setup', [
                '--deploy' => true,
            ]);

            $output = Artisan::output();

            if ($exitCode === 0) {
                return redirect()->route('admin.settings.index')
                    ->with('success', 'Cloudflare Worker deployed successfully!<br><pre class="mt-2 text-xs font-mono bg-slate-900 text-slate-100 p-3 rounded-lg overflow-x-auto select-all">'.e($output).'</pre>');
            }

            return redirect()->route('admin.settings.index')
                ->with('error', 'Cloudflare Worker deployment failed.<br><pre class="mt-2 text-xs font-mono bg-red-950 text-red-200 p-3 rounded-lg overflow-x-auto select-all">'.e($output).'</pre>');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.index')
                ->with('error', 'An error occurred during deployment: '.$e->getMessage());
        }
    }
}
