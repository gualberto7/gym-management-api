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

    public function store($gymId, Request $request)
    {
        if(!auth()->user()->allowedGym($gymId)) {
            abort(403);
        }

        $request->validate([
            'member_id' => 'required',
        ]);

        $entry = Entry::create([
            'member_id' => $request->member_id,
            'gym_id' => $gymId,
            'created_by' => auth()->user()->name,
        ]);

        return response()->json($entry, 201);
    }
}
