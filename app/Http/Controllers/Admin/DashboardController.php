<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LessonService;
use App\Services\QuestDetailService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $lessonService, $scheduleService, $questDetailService, $userService;

    public function __construct(LessonService $lessonService, QuestDetailService $questDetailService, UserService $userService){
        $this->lessonService = $lessonService;
        // $this->scheduleService = $scheduleService;
        $this->questDetailService = $questDetailService;
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'season_id' => $request->query('season_id'),
        ];
        $players = $this->userService->paginateDashboard($filters);

        $activeChallenges = 12;
        $inactiveChallenges = 4;
        $totalLessons = 30;
        $totalEvents = 5;

        return view('admin.index', compact(
            'activeChallenges',
            'inactiveChallenges',
            'totalLessons',
            'totalEvents',
            'players'
        ));
    }
}
