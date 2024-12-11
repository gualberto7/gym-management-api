<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::all();
        return response()->json($memberships);
    }

    public function store(Request $request)
    {
        $membership = Membership::create($request->all());
        return response()->json($membership, 201);
    }
}
