<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Season;
use App\Models\User;
use App\Services\SeasonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public $seasonService;
    public function __construct(SeasonService $seasonService)
    {
        $this->seasonService = $seasonService;
    }
    public function index(Request $request)
    {
        $musim = $this->seasonService->lastSeason();
        return view('member.profil.index', compact('musim'));
    }

    public function reset(Request $request)
    {
        $auth = Auth::user();
        return view('member.profil.reset');
    }

    public function update(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($request->user_id);

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Sandi lama tidak sesuai.']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('member.profile')->with('success', 'Sandi berhasil diperbarui.');
    }
}
