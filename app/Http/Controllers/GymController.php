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
        $gyms = [];
        if ($user->hasRole(Roles::OWNER)) {
            $gyms = $user->gyms()->with('memberships')->get();
        }

        if ($user->hasRole(Roles::ADMIN)) {
            $gyms = Gym::whereId($user->assigned_gym)->get();
        }

        return response()->json($gyms);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $gym = $user->gyms()->create($request->all());

        return response()->json($gym, 201);
    }
}
