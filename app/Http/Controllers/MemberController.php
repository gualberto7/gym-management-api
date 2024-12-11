<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::all();
        return response()->json($members, 200);
    }

    public function store(Request $request)
    {
        $member = Member::create($request->all());
        return response()->json($member, 201);
    }
}
