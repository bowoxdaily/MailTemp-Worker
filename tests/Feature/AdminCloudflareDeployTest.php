<?php

use App\Models\User;
use Illuminate\Support\Facades\Artisan;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

function createAdmin(): User
{
    return User::factory()->create([
        'is_admin' => true,
    ]);
}

test('guest cannot deploy cloudflare worker', function () {
    post(route('admin.settings.cloudflare-deploy'))
        ->assertRedirect(route('admin.login'));
});

test('admin can trigger cloudflare worker deploy successfully', function () {
    // Mock Artisan command call
    Artisan::shouldReceive('call')
        ->once()
        ->with('cloudflare:setup', ['--deploy' => true])
        ->andReturn(0);

    Artisan::shouldReceive('output')
        ->once()
        ->andReturn("Deploying secrets...\nDeploying Worker...");

    actingAs(createAdmin())
        ->post(route('admin.settings.cloudflare-deploy'))
        ->assertRedirect(route('admin.settings.cloudflare'))
        ->assertSessionHas('success');
});

test('admin handles cloudflare worker deploy failure', function () {
    // Mock Artisan command call with error code
    Artisan::shouldReceive('call')
        ->once()
        ->with('cloudflare:setup', ['--deploy' => true])
        ->andReturn(1);

    Artisan::shouldReceive('output')
        ->once()
        ->andReturn('Error: Wrangler login required.');

    actingAs(createAdmin())
        ->post(route('admin.settings.cloudflare-deploy'))
        ->assertRedirect(route('admin.settings.cloudflare'))
        ->assertSessionHas('error');
});
