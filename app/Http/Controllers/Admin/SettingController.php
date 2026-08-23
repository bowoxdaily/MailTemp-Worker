<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.general', compact('settings'));
    }

    public function branding(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.branding', compact('settings'));
    }

    public function ads(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.ads', compact('settings'));
    }

    public function cloudflare(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.cloudflare', compact('settings'));
    }

    public function system(): View
    {
        $settings = Setting::all()->keyBy('key');

        $lastRun = Cache::get('scheduler:last_run');
        $schedulerStatus = [
            'last_run' => $lastRun ? Carbon::parse($lastRun) : null,
            'is_running' => $lastRun && Carbon::parse($lastRun)->diffInMinutes(now()) < 5,
            'cron_command' => '* * * * * cd '.base_path().' && php artisan schedule:run >> /dev/null 2>&1',
        ];

        return view('admin.settings.system', compact('settings', 'schedulerStatus'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'settings' => 'nullable|array',
            'settings.*' => 'nullable|string|max:10000',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'remove_logo' => 'nullable|boolean',
            'og_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3072',
            'remove_og_image' => 'nullable|boolean',
            'favicon' => 'nullable|file|mimes:ico,png,svg|max:1024',
            'remove_favicon' => 'nullable|boolean',
        ]);
>>>>>>>
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.general', compact('settings'));
    }

    public function branding(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.branding', compact('settings'));
    }

    public function ads(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.ads', compact('settings'));
    }

    public function cloudflare(): View
    {
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.cloudflare', compact('settings'));
    }

    public function system(): View
    {
        $settings = Setting::all()->keyBy('key');

        $lastRun = Cache::get('scheduler:last_run');
        $schedulerStatus = [
            'last_run' => $lastRun ? Carbon::parse($lastRun) : null,
            'is_running' => $lastRun && Carbon::parse($lastRun)->diffInMinutes(now()) < 5,
            'cron_command' => '* * * * * cd '.base_path().' && php artisan schedule:run >> /dev/null 2>&1',
        ];

        return view('admin.settings.system', compact('settings', 'schedulerStatus'));
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'settings' => 'nullable|array',
            'settings.*' => 'nullable|string|max:10000',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'remove_logo' => 'nullable|boolean',
        ]);

        if ($request->has('settings')) {
            foreach ($request->input('settings') as $key => $value) {
                Setting::set($key, $value);
            }
        }

        if ($request->boolean('remove_logo')) {
            $currentLogo = Setting::get('app_logo_url');
            if ($currentLogo && str_starts_with($currentLogo, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $currentLogo);
                Storage::disk('public')->delete($relativePath);
            }
            Setting::set('app_logo_url', null);
        } elseif ($request->hasFile('app_logo')) {
            $currentLogo = Setting::get('app_logo_url');
            if ($currentLogo && str_starts_with($currentLogo, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $currentLogo);
                Storage::disk('public')->delete($relativePath);
            }

            $path = $request->file('app_logo')->store('branding', 'public');
            Setting::set('app_logo_url', Storage::url($path));
        }

        if ($request->boolean('remove_og_image')) {
            $currentOg = Setting::get('og_image_url');
            if ($currentOg && str_starts_with($currentOg, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $currentOg);
                Storage::disk('public')->delete($relativePath);
            }
            Setting::set('og_image_url', null);
        } elseif ($request->hasFile('og_image')) {
            $currentOg = Setting::get('og_image_url');
            if ($currentOg && str_starts_with($currentOg, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $currentOg);
                Storage::disk('public')->delete($relativePath);
            }

            $path = $request->file('og_image')->store('branding', 'public');
            Setting::set('og_image_url', Storage::url($path));
        }

        if ($request->boolean('remove_favicon')) {
            $currentFavicon = Setting::get('favicon_url');
            if ($currentFavicon && str_starts_with($currentFavicon, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $currentFavicon);
                Storage::disk('public')->delete($relativePath);
            }
            Setting::set('favicon_url', null);
        } elseif ($request->hasFile('favicon')) {
            $currentFavicon = Setting::get('favicon_url');
            if ($currentFavicon && str_starts_with($currentFavicon, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $currentFavicon);
                Storage::disk('public')->delete($relativePath);
            }

            $path = $request->file('favicon')->store('branding', 'public');
            Setting::set('favicon_url', Storage::url($path));
        }
