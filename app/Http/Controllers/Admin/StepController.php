<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Step;
use App\Services\StepService;
use App\Services\TopicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StepController extends Controller
{
    public $stepService, $topicService;

    public function __construct(StepService $stepService, TopicService $topicService)
    {
        $this->stepService = $stepService;
        $this->topicService = $topicService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'step_id' => $request->query('step_id') ?? null,
            'topic_id' => $request->query('topic_id') ?? null,
            'search' => $request->query('q') ?? null,
            'visibility' => $request->query('visibility') ?? null,
        ];
        $topic = $this->topicService->model()->find($filters['topic_id']);
        $data = $this->stepService->paginate($filters);
        return view('admin.materi.panduan.index', compact('data', 'topic'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $auth = Auth::user();
        $params = [
            'step_id' => $request->query('step_id') ?? null,
            'topic_id' => $request->query('topic_id') ?? null,
        ];
        $steps = $this->stepService->byTopic($params['topic_id']);
        $language = $this->stepService->languageByTopic($params['topic_id']);
        return view('admin.materi.panduan.create', compact('steps', 'language'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auth = Auth::user();
            $data = $this->stepService->store($request->toArray(), $auth);
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
    public function show(Step $step)
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
            'step_id' => $request->query('step_id') ?? null,
            'topic_id' => $request->query('topic_id') ?? null,
            'lesson_id'=> $request->query('lesson_id') ?? null,
        ];
        $step = $this->stepService->model()->find($params['step_id']);
        return view('admin.materi.panduan.edit', compact('step'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Step $step)
    {
        try {
            $auth = Auth::user();
            $data = $this->stepService->update($request->toArray(), $auth, $step);
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
    public function destroy(Step $step)
    {
        //
    }
}
