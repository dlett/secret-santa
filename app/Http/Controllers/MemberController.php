<?php

namespace App\Http\Controllers;

use App\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function result(Request $request)
    {
        $member = Member::query()
            ->where('slug', $request->input('slug'))
            ->orWhere('name', $request->input('name'))
            ->firstOrFail();

        if ($member->has_seen_connection) {
            return view('already-seen', compact('member'));
        }

        $member->update(['has_seen_connection' => true]);

        return view('result', compact('member'));
    }
}
