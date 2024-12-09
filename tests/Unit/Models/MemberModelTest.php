<?php

use App\Models\Member;
use App\Models\Subscription;

test('member has many subscription', function () {
    $member = Member::factory()->create();
    Subscription::factory(2)->create(['member_id' => $member->id]);

    $this->assertInstanceOf(Subscription::class, $member->subscriptions[0]);
});
