<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $members = Member::allowedSorts(['name'])->paginate(
            $perPage = $request->input('page.size', 15),
            $columns = ['*'],
            $pageName = 'page[number]',
            $page = $request->input('page.number', 1)
        )->appends($request->only('sort', 'page.size'));
        return response()->json($members, 200);
    }

    public function store(Request $request)
    {
        $member = Member::create($request->all());
        return response()->json($member, 201);
    }
}
