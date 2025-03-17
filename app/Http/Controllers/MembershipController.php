<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index($gymId)
    {
        if(!auth()->user()->allowedGym($gymId)) {
            abort(403);
        }

        $gym = Gym::findOrFail($gymId);
        return response()->json($gym->memberships);
    }

    public function store($gymId, Request $request)
    {
        $user = $request->user();

        if(!$user->allowedGym($gymId)) {
            abort(403);
        }

        $membership = Membership::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'duration_unit' => $request->duration_unit,
            'description' => $request->description,
            'max_checkins' => $request->max_checkins,
            'active' => $request->active,
            'gym_id' => $gymId,
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ]);

        return response()->json($membership, 201);
    }

    public function update($gymId, $membershipId, Request $request)
    {
        if(!auth()->user()->allowedGym($gymId)) {
            abort(403);
        }

        $membership = Membership::findOrFail($membershipId);
        $membership->update($request->all());
        return response()->json($membership, 200);
    }
}
