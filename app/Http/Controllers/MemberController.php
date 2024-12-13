<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::query();
        $sortField = $request->input('sort');
        $sortDirection = 'asc';
        $allowedSorts = ['name'];
        if ($request->filled('sort')) {
            if (Str::of($sortField)->startsWith('-')) {
                $sortDirection = 'desc';
                $sortField = Str::of($sortField)->substr(1);
            }
            if (!in_array($sortField, $allowedSorts)) {
                abort(400, 'Invalid sort field');
            }

            $members->orderBy($sortField, $sortDirection);
        }

        $members = $members->get();
        return response()->json($members, 200);
    }

    public function store(Request $request)
    {
        $member = Member::create($request->all());
        return response()->json($member, 201);
    }
}
