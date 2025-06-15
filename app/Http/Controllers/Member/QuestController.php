<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestController extends Controller
{
    public function index(Request $request)
    {
        return view('member.tantangan.index');
    }
}
