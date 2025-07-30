<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Season;
use App\Services\ActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();
        $search = $request->input('q');
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
            ->get();
        $seasons = Season::all();
        return view('member.aktivitas.index', compact('activities','seasons'));
    }

    public function checklists(Activity $activity)
    {
        $activity->load('checklists.questRequirement'); 
        return response()->json($activity->checklists->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->questRequirement->description ?? '(Deskripsi tidak ditemukan)',
                'is_clear' => $item->is_clear,
            ];
        }));
    }

    public function update(Request $request, Activity $activity)
    {
        try {
            $auth = Auth::user();
            $this->activityService->update($request->toArray(), $auth, $activity);
            return redirect()->route('member.activity')->with('success', 'Status berhasil diubah menjadi ' . strtoupper($request->status));
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
