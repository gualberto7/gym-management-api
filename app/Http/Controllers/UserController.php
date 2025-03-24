<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Enums\Roles;
use Illuminate\Http\Request;

class UserController extends Controller
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

        $gyms = $query->get();

        return response()->json([
            'user' => $user,
            'gyms' => $gyms
        ]);
    }
}
