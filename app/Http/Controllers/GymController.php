<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Gym::query();

        if ($user->hasRole(Roles::OWNER)) {
            $query->where('user_id', $user->id);
        }

        if ($user->hasRole(Roles::ADMIN)) {
            $query->where('id', $user->assigned_gym);
        }

        $gyms = $query->with('memberships')->get();

        return response()->json($gyms);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $gym = $user->gyms()->create($request->all());

        return response()->json($gym, 201);
    }

    public function update(Gym $gym, Request $request)
    {
        $gym->update($request->all());

        return response()->json($gym, 200);
    }
}
