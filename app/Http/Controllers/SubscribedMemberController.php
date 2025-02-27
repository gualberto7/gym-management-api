<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
use App\Models\Member;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SubscribedMemberController extends Controller
{
    public function index()
    {
        if (Gate::denies('viewAny', Subscription::class)) {
            abort(403);
        }

        $gym = auth()->user()->gyms->first();

        $subscriptions = Subscription::with(['member', 'membership'])
            ->where('gym_id', $gym->id)
            ->jsonPaginate();

        return SubscriptionResource::collection($subscriptions);
    }

    public function show($ci)
    {
        // TODO: Implement validation to find subscriptions only for the gym owner or admin

        $member = Member::where('ci', $ci)->first();
        if (!$member) {
            abort(404, 'No hay registros para CI: ' . $ci);
        }

        $subscription = Subscription::with(['member', 'membership'])
            ->where('member_id', $member->id)
            ->first();

        if (!$subscription) {
            abort(404, 'Este usuario no tiene una subscripción');
        }

        return new SubscriptionResource($subscription);
    }

    public function store(Request $request)
    {
        if (Gate::denies('create', Subscription::class)) {
            abort(403);
        }

        $subscription = Subscription::create($request->all());

        return new SubscriptionResource($subscription);
    }
}
