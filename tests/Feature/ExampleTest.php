<?php

use App\Models\User;

test('the application returns a successful response', function () {
    User::factory()->create(['is_admin' => true]);

    $response = $this->get('/');

    $response->assertStatus(200);
});
