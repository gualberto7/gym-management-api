<?php

use App\Models\Subscription;
use App\Http\Resources\SubscriptionResource;

test('susbcription resource returns valid formatted data', function () {
    $subscription = Subscription::factory()->create(['end_date' => now()->subDays(5)]);

    $resource = new SubscriptionResource($subscription);

    $this->assertEquals($subscription->id, $resource->id);
    $this->assertEquals($subscription->member, $resource->member);
    $this->assertEquals($subscription->email, $resource->email);
    $this->assertEquals($subscription->getStatus(), 'active');
    $this->assertEquals($subscription->created_at->toDateTimeString(), $resource->created_at);
    $this->assertEquals($subscription->updated_at->toDateTimeString(), $resource->updated_at);
});
