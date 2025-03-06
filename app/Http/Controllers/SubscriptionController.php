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
        if(!auth()->user()->allowedGym($gymId)) {
            abort(403);
        }

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

    public function store($gymId, Request $request)
    {
        $user = auth()->user();

        if(!$user->allowedGym($gymId)) {
            abort(403);
        }

        $subscription = Subscription::create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'membership_id' => $request->membership_id,
            'member_id' => $request->member_id,
            'gym_id' => $gymId,
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        return new SubscriptionResource($subscription);
    }
}
