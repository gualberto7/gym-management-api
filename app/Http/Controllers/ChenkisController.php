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
        $chenkis = Chenkis::where('gym_id', $gym->id)->jsonPaginate();

        return ChenkisResource::collection($chenkis);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'gym_id' => 'required',
            'created_by' => 'required|string',
        ]);

        $chenki = Chenkis::create([
            'member_id' => $request->member_id,
            'gym_id' => $request->gym_id,
            'created_by' => $request->created_by,
        ]);

        return response()->json($chenki, 201);
    }
}
