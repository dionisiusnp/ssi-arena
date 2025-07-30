<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Topic;
use App\Services\LessonService;
use App\Services\TopicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicController extends Controller
{
    public $topicService, $lessonService;

    public function __construct(TopicService $topicService, LessonService $lessonService)
    {
        $this->topicService = $topicService;
        $this->lessonService = $lessonService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'lesson_id' => $request->query('lesson_id') ?? null,
            'search' => $request->query('q') ?? null,
            'visibility' => $request->query('visibility') ?? null,
        ];
        $lessons = Lesson::where('changed_by', $auth->id)->get();
        if (is_null($filters['lesson_id'])) {
            abort(400, 'Parameter materi tidak ditemukan.');
        }
        $lesson = null;
        if ($filters['lesson_id']) {
            $lesson = $this->lessonService->model()->find($filters['lesson_id']);
            if (!$lesson) {
                abort(404, 'Materi tidak ditemukan.');
            }
            if ($lesson->changed_by !== $auth->id) {
                abort(403, 'Akses tidak diizinkan untuk materi ini.');
            }
        }
        $data = $this->topicService->paginate($filters, $auth);
        return view('admin.materi.topik.index', compact('data', 'lesson', 'lessons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $auth = Auth::user();
        $lessonId = $request->query('lesson_id');

        if (!$lessonId) {
            abort(400, 'Parameter materi dibutuhkan.');
        }

        $lesson = $this->lessonService->model()->find($lessonId);
        if (!$lesson) {
            abort(404, 'Materi tidak ditemukan.');
        }

        if ($lesson->changed_by !== $auth->id) {
            abort(403, 'Akses tidak diizinkan untuk materi ini.');
        }

        $getSequence = $this->topicService->byLesson($lessonId);
        $recentSequence = $getSequence->count() + 1;

        return view('admin.materi.topik.create', compact('recentSequence'));
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
        $auth = Auth::user();
        if ($topic->changed_by !== $auth->id) {
            abort(403, 'Akses tidak diizinkan untuk materi ini.');
        }
        return view('admin.materi.topik.detail', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        $auth = Auth::user();
        if ($topic->changed_by !== $auth->id) {
            abort(403, 'Akses tidak diizinkan untuk materi ini.');
        }
        $getSequence = $this->topicService->byLesson($topic->lesson_id);
        $recentSequence = $getSequence->count() + 1;
        return view('admin.materi.topik.edit', compact('topic', 'recentSequence'));
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
