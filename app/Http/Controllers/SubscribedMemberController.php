<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubscriptionResource;
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

        $members = $gym->subscriptions()
            ->join('members', 'subscriptions.member_id', '=', 'members.id')
            ->join('memberships', 'subscriptions.membership_id', '=', 'memberships.id')
            ->select('members.*', 'memberships.name AS membership', 'subscriptions.start_date', 'subscriptions.end_date')
            ->paginate();

        return SubscriptionResource::collection($members);
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
