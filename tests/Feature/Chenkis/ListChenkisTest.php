<?php

use App\Models\Chenkis;
use App\Models\Gym;
use App\Models\User;
use App\Models\Member;

test('guest users cannot list chenkis', function () {
    $response = $this->getJson(route('api.chenkis.index'));

    $response->assertStatus(401);
});

test('admins can list chenkis of their members', function () {
    $user = User::factory()->create(['role' => 'admin']);
    Gym::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->getJson(route('api.chenkis.index'));

    $response->assertStatus(200);
});

test('owners can list chenkis of their members', function () {
    $user = User::factory()->create(['role' => 'owner']);
    Gym::factory()->create(['user_id' => $user->id]);

    $response = $this->actingAs($user)
        ->getJson(route('api.chenkis.index'));

    $response->assertStatus(200);
});

test("verify chenkis belogs to owner's / admin's gyms", function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');
    Chenkis::factory()->create(['gym_id' => $data['gym']->id, 'member_id' => $data['member']->id]);

    $data1 = $this->createUserGymMembershipAndSubscription('owner');
    Chenkis::factory()->create(['gym_id' => $data1['gym']->id, 'member_id' => $data1['member']->id]);

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.chenkis.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(1);
});

test('verify chenkis response data structure', function () {
    $data = $this->createUserGymMembershipAndSubscription('owner');
    $chenkis = Chenkis::factory()->create(['gym_id' => $data['gym']->id, 'member_id' => $data['member']->id]);

    $response = $this->actingAs($data['user'])
        ->getJson(route('api.chenkis.index'));

    $response->assertJsonStructure([
        '*' => ['id', 'member_name', 'member_phone', 'created_at', 'created_by'],
    ]);
});
