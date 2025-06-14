<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roadmap;
use App\Services\RoadmapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoadmapController extends Controller
{
    public $roadmapService;

    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
            'role' => $request->query('role') ?? null,
            'visibility' => $request->query('visibility') ?? null,
        ];
        $data = $this->roadmapService->paginate($filters);
        return view('admin.rute.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rute.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $tops = $request->topics ?? [];
            unset($request->topics);
            $data = $this->roadmapService->store($request->toArray(), $tops,$auth);
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
    public function show(Roadmap $roadmap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Roadmap $roadmap)
    {
        return view('admin.rute.edit', compact('roadmap'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Roadmap $roadmap)
    {
        try {
            $auth = Auth::user();
            $tops = $request->topics ?? [];
            unset($request->topics);
            $data = $this->roadmapService->update($request->toArray(), $tops,$auth, $roadmap);
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
    public function destroy(Roadmap $roadmap)
    {
        //
    }
}
