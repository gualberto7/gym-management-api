<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\SubscriptionResource;

class SubscriptionController extends Controller
{
    public function index($gymId)
    {
        if(!auth()->user()->allowedGym($gymId)) {
            abort(403);
        }

        $subscriptions = Subscription::with(['member', 'membership'])
            ->where('gym_id', $gymId)
            ->jsonPaginate();

        return SubscriptionResource::collection($subscriptions);
    }

    public function show($gymId, $ci)
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
