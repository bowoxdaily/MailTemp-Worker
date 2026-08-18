<?php

use App\Models\Setting;
use App\Models\User;

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
