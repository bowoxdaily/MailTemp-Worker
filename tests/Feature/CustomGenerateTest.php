<?php

use App\Models\Domain;
use App\Models\User;

beforeEach(function () {
    User::factory()->create(['is_admin' => true]);
});

test('it can generate email with custom username and domain', function () {
    // Setup active domain
    $domain = Domain::create([
        'domain' => 'testmail.com',
        'is_active' => true,
    ]);

    $response = $this->postJson('/api/v1/generate', [
        'username' => 'kustomuser',
        'domain' => 'testmail.com',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'email' => 'kustomuser@testmail.com',
        ]);

    $this->assertDatabaseHas('temporary_emails', [
        'email_address' => 'kustomuser@testmail.com',
        'domain_id' => $domain->id,
    ]);
});

test('it fails to generate email with inactive or non-existent domain', function () {
    Domain::create([
        'domain' => 'inactive.com',
        'is_active' => false,
    ]);

    $response = $this->postJson('/api/v1/generate', [
        'username' => 'kustomuser',
        'domain' => 'inactive.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['domain']);

    $response2 = $this->postJson('/api/v1/generate', [
        'username' => 'kustomuser',
        'domain' => 'nonexistent.com',
    ]);

    $response2->assertStatus(422)
        ->assertJsonValidationErrors(['domain']);
});

test('it fails to generate email with invalid username format', function () {
    $domain = Domain::create([
        'domain' => 'testmail.com',
        'is_active' => true,
    ]);

    $response = $this->postJson('/api/v1/generate', [
        'username' => 'invalid user!',
        'domain' => 'testmail.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['username']);
});
