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
}
