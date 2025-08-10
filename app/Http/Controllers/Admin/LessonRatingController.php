<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LessonRating;
use App\Services\LessonRatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonRatingController extends Controller
{
    public $lessonRatingService;

    public function __construct(LessonRatingService $lessonRatingService)
    {
        $this->lessonRatingService = $lessonRatingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            $data = $this->lessonRatingService->store($request->toArray(),$auth);
            return response()->json([
                'success' => true,
                'message' => 'Rating berhasil disimpan',
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
    public function show(LessonRating $lessonRating)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LessonRating $lessonRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LessonRating $lessonRating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LessonRating $lesson_rating)
    {
        try {
            $this->lessonRatingService->destroy($lesson_rating);
            return response()->json([
                'success' => true,
                'message' => 'Rating berhasil dibatalkan',
            ]);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
