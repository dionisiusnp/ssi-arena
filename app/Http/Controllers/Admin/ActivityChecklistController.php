<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityChecklist;
use App\Services\ActivityChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityChecklistController extends Controller
{
    public $activityChecklistService;

    public function __construct(ActivityChecklistService $activityChecklistService)
    {
        $this->activityChecklistService = $activityChecklistService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityChecklist $activityChecklist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityChecklist $activityChecklist)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ActivityChecklist $activityChecklist)
    {
        //
    }

    public function toggleStatus(Request $request, ActivityChecklist $activity_checklist)
    {
        try {
            $auth = Auth::user();
            $this->activityChecklistService->isClear($auth, $activity_checklist);

            // Ambil parameter jika tidak null
            $query = array_filter([
                'claimed_by' => $request->query('claimed_by'),
                'season_id'  => $request->query('season_id'),
                'search'     => $request->query('search'),
            ]);

            return redirect()
                ->route('activity.show', [
                    'activity' => $activity_checklist->activity_id,
                ] + $query)
                ->with('success', 'Status checklist berhasil diubah.');
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityChecklist $activityChecklist)
    {
        //
    }
}
