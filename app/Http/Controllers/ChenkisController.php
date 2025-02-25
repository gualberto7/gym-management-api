<?php

namespace App\Http\Controllers;

use App\Models\Chenkis;
use Illuminate\Http\Request;

class ChenkisController extends Controller
{
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
