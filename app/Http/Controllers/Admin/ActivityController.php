<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Services\ActivityService;
use App\Services\SeasonService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public $activityService, $seasonService, $userService;

    public function __construct(ActivityService $activityService, SeasonService $seasonService, UserService $userService)
    {
        $this->activityService = $activityService;
        $this->seasonService = $seasonService;
        $this->userService = $userService;
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

        $baseQuery = $this->activityService->model()->with('detail')
            ->when($filters['claimed_by'], fn($q) => $q->where('claimed_by', $filters['claimed_by']))
            ->when($filters['season_id'], function ($q) use ($filters) {
                $q->whereHas('detail', function ($q2) use ($filters) {
                    $q2->where('season_id', $filters['season_id']);
                });
            });

        $taskClear   = (clone $baseQuery)->where('status', true)->get();
        $taskUnclear = (clone $baseQuery)->where('status', false)->get();

        $totalSelesai = $taskClear->sum(fn($activity) => $activity->detail->point + ($activity->detail->point * $activity->detail->point_multiple));
        $totalBelum   = $taskUnclear->sum(fn($activity) => $activity->detail->point + ($activity->detail->point * $activity->detail->point_multiple));

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

    public function toggleStatus(Activity $activity)
    {
        try {
            $auth = Auth::user();
            $data = $this->activityService->isClear($auth, $activity);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
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
