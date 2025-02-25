<?php

namespace App\Http\Controllers;

use App\Models\Chenkis;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;

class ChenkisController extends Controller
{

    public function index()
    {
        $gym = auth()->user()->gyms->first();

        if (!$gym) {
            return response()->json(['error' => 'No gym associated with the user'], 404);
        }
        $chenkis = Chenkis::where('gym_id', $gym->id)->get();
        return response()->json($chenkis, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'gym_id' => 'required',
            'registred_by' => 'required|string',
        ]);

        $chenki = Chenkis::create([
            'member_id' => $request->member_id,
            'gym_id' => $request->gym_id,
            'registred_by' => $request->registred_by,
        ]);

        return response()->json($chenki, 201);
    }
}
