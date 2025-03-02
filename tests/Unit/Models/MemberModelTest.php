<?php

use App\Models\Member;
use App\Models\Entry;
use App\Models\Subscription;

test('member has many subscription', function () {
    $member = Member::factory()->create();
    Subscription::factory(2)->create(['member_id' => $member->id]);

    $this->assertInstanceOf(Subscription::class, $member->subscriptions[0]);
});

test('member has many entries', function () {
    $member = Member::factory()->create();
    Entry::factory(2)->create(['member_id' => $member->id]);

    $this->assertInstanceOf(Entry::class, $member->entries[0]);
});
