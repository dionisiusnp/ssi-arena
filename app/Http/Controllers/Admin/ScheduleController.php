<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
            'is_active' => $request->query('is_active') ?? null,
        ];
        $data = $this->scheduleService->paginate($filters);
        return view('admin.acara.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.acara.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->scheduleService->store($request->toArray(), $auth);
            if ($request->hasFile('schedule_img')) {
                $data->clearMediaCollection('schedule_img');
                $data->addMediaFromRequest('schedule_img')->toMediaCollection('schedule_img', 'media');
            }
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
    public function show(Schedule $schedule)
    {
        return view('admin.acara.detail', compact('$schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        return view('admin.acara.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        try {
            $auth = Auth::user();
            $data = $this->scheduleService->update($request->toArray(), $auth, $schedule);
            if ($request->hasFile('schedule_img')) {
                $schedule->clearMediaCollection('schedule_img');
                $schedule->addMediaFromRequest('schedule_img')->toMediaCollection('schedule_img', 'media');
            }
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function toggleStatus(Schedule $schedule)
    {
        try {
            $auth = Auth::user();
            $this->scheduleService->isActive($auth, $schedule);
            
            return redirect()
                ->route('schedule.index')
                ->with('success', 'Status berhasil diperbarui.');
        } catch (\Throwable $th) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui status: ' . $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        // 
    }
}
