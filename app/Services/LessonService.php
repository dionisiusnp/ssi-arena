<?php

namespace App\Services;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LessonService
{
    protected Model $model;
    public $topicService, $stepService;
    /**
     * Create a new class instance.
     */
    public function __construct(Lesson $lesson, TopicService $topicService, StepService $stepService)
    {
        $this->model = $lesson;
        $this->topicService = $topicService;
        $this->stepService = $stepService;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('lessons.id as id'),
                DB::raw('lessons.name as text'),
            ])
            ->when(count($filters), function ($q) use ($filters) {
                $q->where($filters);
            })
            ->limit(10);
        return $data;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $role = $filter['role'] ?? null;
        $visibility = $filter['visibility'] ?? null;

        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($visibility, function ($query) use ($visibility) {
                $query->where('visibility', $visibility);
            })
            ->withCount('topics')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function store(array $data, array $topics, $auth)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $lesson = $this->model->create($data);

            if (!empty($topics)) {
                $i = 1;
                foreach ($topics as $topic) {
                    $this->topicService->model()->create([
                        'lesson_id' => $lesson->id,
                        'name' => $topic['name'],
                        'description' => $topic['description'],
                        'sequence' => $i++,
                        'changed_by' => $auth->id,
                    ]);
                }
            }
            DB::commit();
            return $lesson;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, array $topics, $auth, Lesson $lesson)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $lesson->update($data);
            $existingTopics = $this->topicService->byLesson($lesson->id);
            
            if (!empty($topics)) {
                if ($existingTopics->isNotEmpty()) {
                    foreach ($existingTopics as $topic) {
                        $topic->steps()->delete();
                        $topic->delete();
                    }
                }
                foreach ($topics as $i => $topic) {
                    $this->topicService->model()->create([
                        'lesson_id' => $lesson->id,
                        'name' => $topic['name'],
                        'description' => $topic['description'],
                        'sequence' => $i + 1,
                        'changed_by' => $auth->id,
                    ]);
                }
            }
            
            DB::commit();
            return $lesson;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Lesson $lesson): bool
    {
        try {
            return $lesson->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
