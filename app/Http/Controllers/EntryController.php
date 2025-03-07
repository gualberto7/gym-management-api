<?php

namespace App\Http\Controllers;

use App\Http\Resources\EntryResource;
use App\Models\Entry;
use App\Models\Gym;
use Illuminate\Http\Request;

class EntryController extends Controller
{

    public function index($gymId)
    {
        if(!auth()->user()->allowedGym($gymId)) {
            abort(403);
        }

        $gym = Gym::findOrFail($gymId);

        if (!$gym) {
            return response()->json(['error' => 'No gym associated with the user'], 404);
        }
        $entries = Entry::where('gym_id', $gym->id)->jsonPaginate();

        return EntryResource::collection($entries);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required',
            'gym_id' => 'required',
            'created_by' => 'required|string',
        ]);

        $entry = Entry::create([
            'member_id' => $request->member_id,
            'gym_id' => $request->gym_id,
            'created_by' => $request->created_by,
        ]);

        return response()->json($entry, 201);
    }
}
