<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\LessonService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public $lessonService;

    public function __construct(LessonService $lessonService)
    {
        $this->lessonService = $lessonService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
            'role' => $request->query('role') ?? null,
        ];
        $lessons = $this->lessonService->paginate($filters);
        return view('member.materi.index', compact('lessons'));
    }
}
