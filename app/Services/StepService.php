<?php

namespace App\Services;

use App\Models\Step;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StepService
{
    protected Model $model;
    public $topicService;
    /**
     * Create a new class instance.
     */
    public function __construct(Step $step, TopicService $topicService)
    {
        $this->model = $step;
        $this->topicService = $topicService;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('steps.id as id'),
                DB::raw('steps.name as text'),
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
        $topicId = $filter['topic_id'] ?? null;
        $visibility = $filter['visibility'] ?? null;

        return $this->model
            ->when($topicId, function ($query) use ($topicId) {
                $query->where('topic_id', $topicId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($visibility, function ($query) use ($visibility) {
                $query->where('visibility', $visibility);
            })
            ->orderBy('sequence')
            ->paginate($perPage);
    }

    public function byTopic($topicId)
    {
        try {
            return $this->model->where('topic_id','=',$topicId)->get();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function languageByTopic($topicId)
    {
        return $this->model->where('topic_id','=',$topicId)->pluck('language')->first();
    }

    public function store(array $data, $auth)
    {
        DB::beginTransaction();
        try {
            $topic = $this->topicService->model()->find($data['topic_id']);

            if (!empty($data['steps'])) {
                $topic->steps()->delete();
                $i = 1;
                foreach ($data['steps'] as $step) {
                    $this->model()->create([
                        'topic_id' => $data['topic_id'],
                        'language' => $data['language'],
                        'name' => $step['name'],
                        'content_type' => $step['content_type'],
                        'content_input' => $step['content_input'],
                        'content_output' => $step['content_output'],
                        'sequence' => $i++,
                        'changed_by' => $auth->id,
                    ]);
                }
            }

            DB::commit();
            return true;

        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, $auth, Step $step)
    {
        try {
            $data['changed_by'] = $auth->id;
            $step->update($data);
            return $step;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Step $step): bool
    {
        try {
            return $step->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
