<?php

namespace App\Http\Controllers\Member;

use App\Enums\SettingGroupEnum;
use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Models\Setting;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    public $userService, $seasonService;
    public function __construct(UserService $userService, SeasonService $seasonService)
    {
        $this->userService = $userService;
        $this->seasonService = $seasonService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();
        $lastSeason = $this->seasonService->lastSeason() ?? null;
        $filters = [
            'season_id' => $request->query('season_id') ?? optional($lastSeason)->id,
        ];
        $players = $this->userService->paginateDashboard($filters);
        $seasons = Season::all();
        $winnersCount = Setting::where('group', SettingGroupEnum::GENERAL->value)->where('key','winner_counter')->value('current_value') ?? 0;
        return view('member.peringkat.index', compact('players', 'seasons', 'winnersCount'));
    }
}
