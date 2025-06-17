<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\ActivityService;
use App\Services\QuestDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestController extends Controller
{
    public $questDetailService, $activityService;
    public function __construct(QuestDetailService $questDetailService, ActivityService $activityService)
    {
        $this->questDetailService = $questDetailService;
        $this->activityService = $activityService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
        ];
        $quests = $this->questDetailService->paginateMember($filters, 10, $auth);
        return view('member.tantangan.index', compact('quests'));
    }

    public function claim($id)
    {
        $auth = Auth::user();
        $this->activityService->questClaim($id, $auth);
        return redirect()->route('member.quest')->with('success','Sukses mengambil tantangan.');
    }
}
