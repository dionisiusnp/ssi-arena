<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\Services\RoadmapService;
use App\Services\TopicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public $topicService, $roadmapService;

    public function __construct(TopicService $topicService, RoadmapService $roadmapService)
    {
        $this->topicService = $topicService;
        $this->roadmapService = $roadmapService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'roadmap_id' => $request->query('roadmap_id') ?? null,
            'search' => $request->query('q') ?? null,
            'visibility' => $request->query('visibility') ?? null,
        ];
        $roadmap = $this->roadmapService->model()->find($filters['roadmap_id']);
        $data = $this->topicService->paginate($filters);
        return view('admin.materi.topik.index', compact('data', 'roadmap'));
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
            $data = $this->topicService->store($request->toArray(), $auth);
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
    public function show(Topic $topic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        return view('admin.materi.topik.edit', compact('topic'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        try {
            $auth = Auth::user();
            $data = $this->topicService->update($request->toArray(), $auth, $topic);
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
    public function destroy(Topic $topic)
    {
        //
    }
}
