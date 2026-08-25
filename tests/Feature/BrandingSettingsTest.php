<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    User::factory()->create(['is_admin' => true]);
});

test('homepage renders dynamic branding setting values', function () {
    Setting::set('app_name', 'SuperTempMail');
    Setting::set('footer_copyright', 'SuperTempMail footer text');

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('SuperTempMail');
    $response->assertSee('SuperTempMail footer text');
});

test('homepage renders logo image when logo url is set', function () {
    Setting::set('app_name', 'SuperTempMail');
    Setting::set('app_logo_url', 'https://example.com/logo.png');

    $response = $this->get('/');

    $response->assertStatus(200);
    // Flexible assertions to handle auto-formatted whitespace
    $response->assertSee('https://example.com/logo.png');
    $response->assertSee('SuperTempMail');
});

test('admin can update branding settings', function () {
    $admin = User::factory()->create([
        'is_admin' => true,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'settings' => [
            'app_name' => 'AdminUpdatedName',
            'app_logo_url' => 'https://example.com/admin.png',
            'footer_copyright' => 'AdminUpdatedFooter',
        ],
    ]);

    $response->assertRedirect(route('admin.settings.index'));

    expect(Setting::get('app_name'))->toBe('AdminUpdatedName');
    expect(Setting::get('app_logo_url'))->toBe('https://example.com/admin.png');
    expect(Setting::get('footer_copyright'))->toBe('AdminUpdatedFooter');
});

test('admin can update ad slots and homepage renders them', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'settings' => [
            'ad_header' => '<div>header-ad</div>',
            'ad_generator' => '<div>generator-ad</div>',
            'ad_inbox' => '<div>inbox-ad</div>',
            'ad_footer' => '<div>footer-ad</div>',
        ],
    ]);

    $response->assertRedirect(route('admin.settings.index'));

    $this->get('/')->assertSee('header-ad')->assertSee('generator-ad')->assertSee('inbox-ad')->assertSee('footer-ad');
});

test('admin can upload brand logo file and remove it', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $file = UploadedFile::fake()->image('logo.png', 200, 50);

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'app_logo' => $file,
        'settings' => [
            'app_logo_height' => '45',
        ],
    ]);

    $response->assertRedirect(route('admin.settings.index'));

    $logoUrl = Setting::get('app_logo_url');
    expect($logoUrl)->not->toBeNull();
    expect(Setting::get('app_logo_height'))->toBe('45');

    $storedPath = str_replace('/storage/', '', $logoUrl);
    Storage::disk('public')->assertExists($storedPath);

    // Remove logo test
    $removeResponse = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'remove_logo' => '1',
    ]);

    $removeResponse->assertRedirect(route('admin.settings.index'));
    expect(Setting::get('app_logo_url'))->toBeNull();
    Storage::disk('public')->assertMissing($storedPath);
});

test('admin can upload favicon file and remove it', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);
    $file = UploadedFile::fake()->create('favicon.png', 10, 'image/png');

    $response = $this->actingAs($admin)->put(route('admin.settings.update'), [
        'favicon' => $file,
    ]);

    $response->assertRedirect(route('admin.settings.index'));

    $faviconUrl = Setting::get('favicon_url');
    expect($faviconUrl)->not->toBeNull();
    $storedPath = str_replace('/storage/', '', $faviconUrl);
    Storage::disk('public')->assertExists($storedPath);

    $this->actingAs($admin)->put(route('admin.settings.update'), ['remove_favicon' => '1']);

    expect(Setting::get('favicon_url'))->toBeNull();
    Storage::disk('public')->assertMissing($storedPath);
});
