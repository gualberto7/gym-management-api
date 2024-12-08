<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('can request a user by cookies', function () {
    $user = User::factory()->create(['email' => 'user@test.com']);
    Sanctum::actingAs($user);
    $response = $this->get(route('api.user.find'));
    $response->assertStatus(200);
    $response->assertJson([
        'email' => 'user@test.com',
    ]);
});
