<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SettingGroupEnum;
use App\Enums\VisibilityEnum;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\QuestDetail;
use App\Models\Schedule;
use App\Models\Setting;
use App\Services\LessonService;
use App\Services\QuestDetailService;
use App\Services\ScheduleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $lessonService, $scheduleService, $questDetailService, $userService;

    public function __construct(ScheduleService $scheduleService, LessonService $lessonService, QuestDetailService $questDetailService, UserService $userService){
        $this->lessonService = $lessonService;
        $this->scheduleService = $scheduleService;
        $this->questDetailService = $questDetailService;
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();

        $seasonId = $request->query('season_id');

        $filters = [
            'season_id' => $seasonId,
        ];

        $players = $this->userService->paginateDashboard($filters);
        $events = Schedule::where('is_active', true)->get();
        $lessons = Lesson::whereNotIn('visibility', [VisibilityEnum::DRAFT->value])->get();

        // Ambil quest detail tergantung season_id
        $detailsActiveQuery = QuestDetail::where('is_editable', false);
        $detailsInActiveQuery = QuestDetail::where('is_editable', true);
        if (!empty($seasonId)) {
            $detailsActiveQuery->where('season_id', $seasonId);
            $detailsInActiveQuery->where('season_id', $seasonId);
        }
        $detailsActive = $detailsActiveQuery->get();
        $detailsInActive = $detailsInActiveQuery->get();

        $activeChallenges = $detailsActive->count();
        $inactiveChallenges = $detailsInActive->count();
        $totalLessons = $lessons->count();
        $totalEvents = $events->count();
        $winnersCount = Setting::where('group', SettingGroupEnum::GENERAL->value)->where('key','winner_counter')->value('current_value') ?? 0;
        return view('admin.index', compact(
            'activeChallenges',
            'inactiveChallenges',
            'totalLessons',
            'totalEvents',
            'players',
            'winnersCount'
        ));
    }
}
