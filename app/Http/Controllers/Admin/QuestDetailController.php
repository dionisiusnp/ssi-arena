<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestDetail;
use App\Models\Season;
use App\Models\User;
use App\Services\QuestDetailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestDetailController extends Controller
{
    public $questDetailService;

    public function __construct(QuestDetailService $questDetailService)
    {
        $this->questDetailService = $questDetailService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
            'is_editable' => $request->query('is_editable') ?? null,
        ];
        $seasons = Season::all();
        $questDetails = $this->questDetailService->paginate($filters);
        return view('admin.tantangan.index', compact('questDetails', 'seasons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tantangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $reqs = $request->requirements ?? null;
            unset($request->requirements);
            $data = $this->questDetailService->store($request->toArray(), $reqs, $auth);
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
    public function show(QuestDetail $questDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestDetail $quest_detail)
    {
        $selectedPlayers = json_decode($quest_detail->claimable_by) ?? [];
        $players = User::whereIn('id', $selectedPlayers)->get();
        return view('admin.tantangan.edit', compact('quest_detail', 'players'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestDetail $quest_detail)
    {
        try {
            $auth = Auth::user();
            $reqs = $request->requirements ?? null;
            unset($request->requirements);
            $data = $this->questDetailService->update($request->toArray(),$reqs, $auth, $quest_detail);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function toggleStatus(QuestDetail $quest_detail)
    {
        try {
            $auth = Auth::user();
            $this->questDetailService->isEditable($auth, $quest_detail);
            return redirect()->route('quest-detail.index')->with('success', 'Status tantangan berhasil diubah.');
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestDetail $quest_detail)
    {
        try {
            $this->questDetailService->destroy($quest_detail);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
