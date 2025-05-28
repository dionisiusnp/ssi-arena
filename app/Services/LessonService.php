<?php

namespace App\Services;

use App\Models\Lesson;

class LessonService
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Lesson $lesson)
    {
        $this->model = $lesson;
    }

    public function model()
    {
        return $this->model;
    }
}
