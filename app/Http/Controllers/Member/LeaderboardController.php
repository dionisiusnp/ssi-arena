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
        $seasonId = $request->input('season_id');
        $winnersCount = Setting::where('group', SettingGroupEnum::GENERAL->value)->where('key','winner_counter')->value('current_value') ?? 0;
        $topPlayers = $winnersCount > 0 ? $this->userService->getTopPlayers($seasonId, $winnersCount) : collect();
        $topPlayerRanks = [];
        foreach ($topPlayers as $index => $player) {
            $topPlayerRanks[$player->id] = $index; // juara ke-index (0: juara 1)
        }
        $players = $this->userService->getLeaderboardPlayers($seasonId);
        $seasons = Season::all();
        return view('member.peringkat.index', compact(
            'topPlayers',
            'players',
            'topPlayerRanks',
            'seasonId',
            'seasons'
        ));
    }
}
