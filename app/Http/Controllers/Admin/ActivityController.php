<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
        ];
        $data = $this->activityService->paginate($filters);
        return view('admin.aktivitas.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.aktivitas.create');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        return view('admin.aktivitas.edit', compact('activity'));
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
            $data = $this->activityService->isActive($auth, $activity);
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
