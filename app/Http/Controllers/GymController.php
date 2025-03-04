<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GymController extends Controller
{
    public function index(Request $request)
    {
        $gyms = $request->user()->gyms()->with('memberships')->get();

        return response()->json($gyms);
    }

    public function store(Request $request)
    {
        $user = $request->user();

//        if(!$user->can('create gyms')) {
//            abort(403);
//        }

        $gym = $user->gyms()->create($request->all());

        return response()->json($gym, 201);
    }
}
