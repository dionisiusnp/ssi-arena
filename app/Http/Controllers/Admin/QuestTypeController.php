<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestType;
use App\Services\QuestTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestTypeController extends Controller
{
    public $questTypeService;

    public function __construct(QuestTypeService $questTypeService)
    {
        $this->questTypeService = $questTypeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.siklus.tipe.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->questTypeService->store($request->toArray(), $auth);
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
    public function show(QuestType $questType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestType $quest_type)
    {
        return view('admin.siklus.tipe.edit', compact('quest_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestType $quest_type)
    {
        try {
            $auth = Auth::user();
            $data = $this->questTypeService->update($request->toArray(), $auth, $quest_type);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diubah',
                'data'    => $data,
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function toggleStatus(QuestType $quest_type)
    {
        try {
            $auth = Auth::user();
            $this->questTypeService->isActive($auth, $quest_type);

            return redirect()
                ->route('season.index', ['tab' => 'quest-types'])
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
    public function destroy(QuestType $quest_type)
    {
        try {
            $this->questTypeService->destroy($quest_type);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
