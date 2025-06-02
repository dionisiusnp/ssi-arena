<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestLevel;
use App\Services\QuestLevelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestLevelController extends Controller
{
    public $questLevelService;

    public function __construct(QuestLevelService $questLevelService)
    {
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
            'is_active' => $request->query('is_active') ?? null,
        ];
        $data = $this->questLevelService->paginate($filters);
        return view('admin.level-tantangan.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.level-tantangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->questLevelService->store($request->toArray(), $auth);
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
    public function show(QuestLevel $QuestLevel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestLevel $quest_level)
    {
        return view('admin.level-tantangan.edit', compact('quest_level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestLevel $quest_level)
    {
        try {
            $auth = Auth::user();
            $data = $this->questLevelService->update($request->toArray(), $auth, $quest_level);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function toggleStatus(QuestLevel $quest_level)
    {
        try {
            $auth = Auth::user();
            $data = $this->questLevelService->isActive($auth, $quest_level);
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
    public function destroy(QuestLevel $quest_level)
    {
        try {
            $this->questLevelService->destroy($quest_level);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
