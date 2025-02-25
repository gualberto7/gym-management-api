<?php

use App\Models\Member;
use App\Models\Chenkis;
use App\Models\Subscription;

test('member has many subscription', function () {
    $member = Member::factory()->create();
    Subscription::factory(2)->create(['member_id' => $member->id]);

    $this->assertInstanceOf(Subscription::class, $member->subscriptions[0]);
});

test('member has many chenkis', function () {
    $member = Member::factory()->create();
    $chenkis = Chenkis::factory(2)->create(['member_id' => $member->id]);

    $this->assertInstanceOf(Chenkis::class, $member->chenkis[0]);
});
