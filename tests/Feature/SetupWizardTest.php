<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home redirects to setup when no admin exists', function () {
    $this->get('/')->assertRedirect(route('setup.index'));
});

test('setup page loads when no admin exists', function () {
    $this->get('/setup')->assertOk()->assertSee('Create Admin Account');
});

test('setup redirects to admin dashboard when admin exists', function () {
    User::factory()->create(['is_admin' => true]);

    $this->get('/setup')->assertRedirect(route('admin.dashboard'));
});

test('home loads normally when admin exists', function () {
    User::factory()->create(['is_admin' => true]);

    $this->get('/')->assertOk();
});

test('setup store creates admin and redirects to cloudflare', function () {
    $this->post('/setup', [
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('setup.cloudflare'));

    $this->assertDatabaseHas('users', [
        'email' => 'admin@example.com',
        'is_admin' => true,
    ]);
});

test('setup store validates required fields', function () {
    $this->post('/setup', [])->assertSessionHasErrors(['name', 'email', 'password']);
});

test('setup store blocked when admin already exists', function () {
    User::factory()->create(['is_admin' => true]);

    $this->post('/setup', [
        'name' => 'Hacker',
        'email' => 'hacker@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertRedirect(route('admin.dashboard'));

    $this->assertDatabaseMissing('users', ['email' => 'hacker@example.com']);
});

test('cloudflare page loads after admin created', function () {
    User::factory()->create(['is_admin' => true]);

    $this->get('/setup/cloudflare')
        ->assertOk()
        ->assertSee('Deploy Cloudflare Worker')
        ->assertSee('/health')
        ->assertSee('Catch-All');
});

test('cloudflare page redirects to setup when no admin', function () {
    $this->get('/setup/cloudflare')->assertRedirect(route('setup.index'));
});

test('cloudflare deploy validates credentials', function () {
    User::factory()->create(['is_admin' => true]);

    $this->post('/setup/deploy-worker', [])
        ->assertSessionHasErrors(['cloudflare_api_token', 'cloudflare_account_id']);
});

test('setup complete page loads after admin created', function () {
    User::factory()->create(['is_admin' => true]);

    $this->get('/setup/complete')
        ->assertOk()
        ->assertSee('Setup Complete')
        ->assertSee('/health')
        ->assertSee('test email');
});

test('setup complete redirects to setup when no admin', function () {
    $this->get('/setup/complete')->assertRedirect(route('setup.index'));
});
