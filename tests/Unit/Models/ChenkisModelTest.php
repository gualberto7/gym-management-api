<?php

use App\Models\Member;
use App\Models\Chenkis;

test('gym belongs to a member', function () {
    $member = Member::factory()->create();
    $chenkis = Chenkis::factory()->create(['member_id' => $member->id]);

    expect($chenkis->member->is($member))->toBeTrue();
});
