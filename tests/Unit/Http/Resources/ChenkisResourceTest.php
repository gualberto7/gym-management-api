<?php

use App\Models\Chenkis;
use App\Http\Resources\ChenkisResource;

test('chenkis resource returns valid formatted data', function () {
    $chenkis = Chenkis::factory()->create();

    $resource = ChenkisResource::make($chenkis)->resolve();

    $this->assertEquals($chenkis->id, $resource['id']);
    $this->assertEquals($chenkis->member->name, $resource['member_name']);
    $this->assertEquals($chenkis->member->phone, $resource['member_phone']);
    $this->assertEquals($chenkis->created_at->toDateTimeString(), $resource['created_at']);
    $this->assertEquals($chenkis->created_by, $resource['created_by']);
});
