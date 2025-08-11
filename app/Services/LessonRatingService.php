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
            $check = $this->model->withTrashed()
                ->where('lesson_id', $data['lesson_id'])
                ->where('rating_by', $auth->id)
                ->first();

            if ($check) {
                $check->restore();
                return $check;
            } else {
                $data['rating_by'] = $auth->id;
                return $this->model->create($data);
            }
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
