<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChenkisResource;
use App\Models\Chenkis;
use Illuminate\Http\Request;

class ChenkisController extends Controller
{

    public function index()
    {
        $gym = auth()->user()->gyms->first();

        if (!$gym) {
            return response()->json(['error' => 'No gym associated with the user'], 404);
        }
        $chenkis = Chenkis::where('gym_id', $gym->id)->paginate();

        $data = ChenkisResource::collection($chenkis);
        return response()->json($data, 200);
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
