<?php

namespace App\Http\Controllers\Member;

use App\Enums\QuestEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Models\Activity;
use App\Models\Season;
use App\Models\User;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public $seasonService, $userService;
    public function __construct(SeasonService $seasonService, UserService $userService)
    {
        $this->seasonService = $seasonService;
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $musim = $this->seasonService->lastSeason();
        $year = $request->input('year', now()->year);
        $userId = Auth::id();

        $years = Season::selectRaw('YEAR(started_at) as year')
        ->union(
            Season::selectRaw('YEAR(finished_at) as year')
        )
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');

        $counted = [
            QuestEnum::CLAIMED->value,
        ];

        $activityCounts = Activity::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->where('claimed_by', $userId)
            ->whereNotIn('status', $counted)
            ->groupByRaw('DATE(created_at)')
            ->pluck('total', 'date');

        $activityLevels = [];
        foreach ($activityCounts as $date => $count) {
            $activityLevels[$date] = match (true) {
                $count >= 4 => 4,
                $count == 3 => 3,
                $count == 2 => 2,
                $count == 1 => 1,
                default => 0,
            };
        }

        return view('member.profil.index', compact('musim', 'year', 'activityLevels', 'years'));
    }

    public function edit()
    {
        return view('member.profil.edit');
    }

    public function update(CreateUserRequest $request, User $user)
    {
        try {
            $data = $this->userService->update($request->toArray(), $user);
            return redirect()->route('member.profile')->with('success', 'Akun berhasil diperbarui.');
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function reset()
    {
        return view('member.profil.reset');
    }

    public function updatePassword(Request $request)
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
