<?php

use App\Models\Member;
use App\Models\Entry;

test('gym belongs to a member', function () {
    $member = Member::factory()->create();
    $entries = Entry::factory()->create(['member_id' => $member->id]);

    expect($entries->member->is($member))->toBeTrue();
});
