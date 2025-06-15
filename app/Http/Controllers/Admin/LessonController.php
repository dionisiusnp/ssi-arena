<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Services\LessonService;
use App\Services\TopicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public $lessonService, $topicService;

    public function __construct(LessonService $lessonService, TopicService $topicService)
    {
        $this->lessonService = $lessonService;
        $this->topicService = $topicService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'roadmap_id' => $request->query('roadmap_id') ?? null,
            'topic_id' => $request->query('topic_id') ?? null,
            'search' => $request->query('q') ?? null,
            'visibility' => $request->query('visibility') ?? null,
        ];
        $topic = $this->topicService->model()->find($filters['topic_id']);
        $data = $this->lessonService->paginate($filters);
        return view('admin.materi.panduan.index', compact('data', 'topic'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $auth = Auth::user();
        $params = [
            'roadmap_id' => $request->query('roadmap_id') ?? null,
            'topic_id' => $request->query('topic_id') ?? null,
        ];
        $lessons = $this->lessonService->byTopic($params['topic_id']);
        $language = $this->lessonService->languageByTopic($params['topic_id']);
        return view('admin.materi.panduan.create', compact('lessons', 'language'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->lessonService->store($request->toArray(), $auth);
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
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $auth = Auth::user();
        $params = [
            'roadmap_id' => $request->query('roadmap_id') ?? null,
            'topic_id' => $request->query('topic_id') ?? null,
            'lesson_id'=> $request->query('lesson_id') ?? null,
        ];
        $lesson = $this->lessonService->model()->find($params['lesson_id']);
        return view('admin.materi.panduan.edit', compact('lesson'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        try {
            $auth = Auth::user();
            $data = $this->lessonService->update($request->toArray(), $auth, $lesson);
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
    public function destroy(Lesson $lesson)
    {
        //
    }
}
