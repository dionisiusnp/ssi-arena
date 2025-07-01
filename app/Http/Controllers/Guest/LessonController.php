<?php

namespace App\Http\Controllers\Guest;

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
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
            'role' => $request->query('role') ?? null,
        ];
        $lessons = $this->lessonService->paginateMember($filters);
        return view('member.materi.index', compact('lessons'));
    }

    public function show(Lesson $lesson)
    {
        $auth = Auth::user();
        $topics = $this->topicService->byLesson($lesson->id);
        return view('member.materi.panduan.index', compact('lesson', 'topics'));
    }
}
