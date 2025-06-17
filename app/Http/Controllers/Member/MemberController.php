<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $auth = Auth::user();
        $search = $request->input('search');
        $seasonId = $request->input('season_id');

        $activities = Activity::with('detail.season')
            ->where('claimed_by', $auth->id)
            ->when($search, fn($q) =>
                $q->whereHas('detail', fn($qd) =>
                    $qd->where('name', 'like', '%' . $search . '%')
                )
            )
            ->when($seasonId, fn($q) =>
                $q->whereHas('detail', fn($qd) =>
                    $qd->where('season_id', $seasonId)
                )
            )
            ->latest()
            ->paginate(6);
        $seasons = Season::all();
        return view('member.profil.index', compact('seasons','activities'));
    }
}
