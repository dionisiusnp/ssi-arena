<?php

namespace App\Services;

use App\Models\Roadmap;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RoadmapService
{
    protected Model $model;
    public $topicService;
    /**
     * Create a new class instance.
     */
    public function __construct(Roadmap $roadmap, TopicService $topicService)
    {
        $this->model = $roadmap;
        $this->topicService = $topicService;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('roadmaps.id as id'),
                DB::raw('roadmaps.name as text'),
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
            $roadmap = $this->model->create($data);

            if (!empty($topics)) {
                $i = 1;
                foreach ($topics as $topic) {
                    $this->topicService->model()->create([
                        'roadmap_id' => $roadmap->id,
                        'visibility' => $roadmap->visibility,
                        'name' => $topic['name'],
                        'description' => $topic['description'],
                        'sequence' => $i++,
                        'changed_by' => $auth->id,
                    ]);
                }
            }
            DB::commit();
            return $roadmap;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, array $topics, $auth, Roadmap $roadmap)
    {
        DB::beginTransaction();

        try {
            $data['changed_by'] = $auth->id;
            $roadmap->update($data);

            $existingTopics = $this->topicService->byRoadmap($roadmap->id);

            if (empty($topics)) {
                if ($existingTopics->isNotEmpty()) {
                    foreach ($existingTopics as $topic) {
                        $topic->update([
                            'visibility' => $roadmap->visibility,
                            'changed_by' => $auth->id,
                        ]);
                    }
                }
            } else {
                if ($existingTopics->isNotEmpty()) {
                    $existingTopics->each->delete();
                }

                foreach ($topics as $i => $topic) {
                    $this->topicService->model()->create([
                        'roadmap_id' => $roadmap->id,
                        'visibility' => $roadmap->visibility,
                        'name' => $topic['name'],
                        'description' => $topic['description'],
                        'sequence' => $i + 1,
                        'changed_by' => $auth->id,
                    ]);
                }
            }

            DB::commit();
            return $roadmap;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Roadmap $roadmap): bool
    {
        try {
            return $roadmap->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
