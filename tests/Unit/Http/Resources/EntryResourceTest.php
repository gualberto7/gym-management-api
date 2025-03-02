<?php

use App\Models\Entry;
use App\Http\Resources\EntryResource;

test('entry resource returns valid formatted data', function () {
    $entries = Entry::factory()->create();

    $resource = EntryResource::make($entries)->resolve();

    $this->assertEquals($entries->id, $resource['id']);
    $this->assertEquals($entries->member->name, $resource['member_name']);
    $this->assertEquals($entries->member->phone, $resource['member_phone']);
    $this->assertEquals($entries->created_at->toDateTimeString(), $resource['created_at']);
    $this->assertEquals($entries->created_by, $resource['created_by']);
});
