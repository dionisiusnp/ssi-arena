<?php

namespace App\Services;

use App\Models\LessonRating;
use Illuminate\Database\Eloquent\Model;

class LessonRatingService
{
    protected Model $model;

    public function __construct(LessonRating $lessonRating)
    {
        $this->model = $lessonRating;
    }

    public function model()
    {
        return $this->model;
    }

    public function store(array $data, $auth)
    {
        try {
            $data['rating_by'] = $auth->id;
            return $this->model->create($data);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(LessonRating $lessonRating): bool
    {
        try {
            return $lessonRating->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
