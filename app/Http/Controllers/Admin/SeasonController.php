<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use App\Services\QuestLevelService;
use App\Services\QuestTypeService;
use App\Services\SeasonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeasonController extends Controller
{
    public $seasonService, $questTypeService, $questLevelService;

    public function __construct(SeasonService $seasonService, QuestTypeService $questTypeService, QuestLevelService $questLevelService)
    {
        $this->seasonService = $seasonService;
        $this->questTypeService = $questTypeService;
        $this->questLevelService = $questLevelService;
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
        $seasons = $this->seasonService->paginate($filters);
        $questTypes = $this->questTypeService->paginate($filters);
        $questLevels = $this->questLevelService->paginate($filters);
        return view('admin.siklus.index', compact('seasons','questTypes','questLevels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.siklus.musim.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->seasonService->store($request->toArray(), $auth);
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
    public function show(Season $season)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Season $season)
    {
        return view('admin.siklus.musim.edit', compact('season'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Season $season)
    {
        try {
            $auth = Auth::user();
            $data = $this->seasonService->update($request->toArray(), $auth, $season);
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
    public function destroy(Season $season)
    {
        try {
            $this->seasonService->destroy($season);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
