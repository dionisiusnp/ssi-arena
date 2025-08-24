<?php

namespace App\Http\Controllers\Admin;

use App\Enums\QuestEnum;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Services\ActivityService;
use App\Services\RewardLogService;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    public $activityService, $seasonService, $userService, $rewardLogService;

    public function __construct(ActivityService $activityService, SeasonService $seasonService, UserService $userService, RewardLogService $rewardLogService)
    {
        $this->activityService = $activityService;
        $this->seasonService = $seasonService;
        $this->userService = $userService;
        $this->rewardLogService = $rewardLogService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [
            'claimed_by' => $request->query('claimed_by'),
            'season_id'  => $request->query('season_id'),
            'search'     => $request->query('search'),
        ];

        $data = $this->activityService->paginate($filters);
        $seasons = $this->seasonService->model()->all();
        $user = $this->userService->model()->find($filters['claimed_by']);

        $clear = [
            QuestEnum::PLUS->value,
            QuestEnum::MINUS->value,
        ];

        $unClear = [
            QuestEnum::CLAIMED->value,
            QuestEnum::TESTING->value,
            QuestEnum::PENDING->value,
        ];

        $baseQuery = $this->activityService->model()->with('detail')
            ->when($filters['claimed_by'], fn($q) => $q->where('claimed_by', $filters['claimed_by']))
            ->when($filters['season_id'], function ($q) use ($filters) {
                $q->whereHas('detail', function ($q2) use ($filters) {
                    $q2->where('season_id', $filters['season_id']);
                });
            })
            ->when($filters['search'], function ($q) use ($filters) {
                $q->whereHas('detail', function ($q2) use ($filters) {
                    $q2->where('name', 'like', '%' . $filters['search'] . '%');
                });
            });

        $taskClear   = (clone $baseQuery)->whereIn('status', $clear)->get();
        $taskUnclear = (clone $baseQuery)->whereIn('status', $unClear)->get();

        $totalSelesai = $taskClear->sum(fn($activity) =>
            ($activity->detail?->point ?? 0) + ($activity->detail?->point_additional ?? 0)
        );
        $totalBelum = $taskUnclear->sum(fn($activity) =>
            ($activity->detail?->point ?? 0) + ($activity->detail?->point_additional ?? 0)
        );

        return view('admin.pemain.misi.index', compact('data', 'user', 'seasons', 'totalSelesai', 'totalBelum'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->activityService->store($request->toArray(), $auth);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dibuat',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        // Jika perlu, eager load relasi
        $activity->load(['detail.season', 'detail.questType', 'detail.questLevel', 'checklists.questRequirement']);

        return view('admin.pemain.misi.detail', [
            'activity' => $activity,
            'user' => $activity->claimedBy,
            'claimed_by' => request('claimed_by'),
            'season_id' => request('season_id'),
            'search' => request('search'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        try {
            $auth = Auth::user();
            $data = $this->activityService->update($request->toArray(), $auth, $activity);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function pointPlus(Request $request, Activity $activity)
    {
        try {
            $auth = Auth::user();
            $this->activityService->update($request->toArray(), $auth, $activity);
            $activity->checklists()->update(['is_clear' => true]);
            $this->rewardLogService->levelAndPoint($activity->quest_detail_id, $activity->id, $activity->claimed_by, QuestEnum::PLUS->value);
            return redirect()->back()->with('success', 'Nilai ditambahkan (PLUS)!');
        } catch (\Throwable $th) {
            Log::error('Gagal proses PLUS: ' . $th->getMessage());
            throw new \ErrorException($th->getMessage());
        }
    }

    public function pointMinus(Request $request, Activity $activity)
    {
        try {
            $auth = Auth::user();
            $this->activityService->update($request->toArray(), $auth, $activity);
            $activity->checklists()->update(['is_clear' => true]);
            $this->rewardLogService->levelAndPoint($activity->quest_detail_id, $activity->id, $activity->claimed_by, QuestEnum::MINUS->value);
            return redirect()->back()->with('success', 'Nilai dikurangi (MINUS)!');
        } catch (\Throwable $th) {
            Log::error('Gagal proses MINUS: ' . $th->getMessage());
            throw new \ErrorException($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        try {
            $this->activityService->destroy($activity);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
