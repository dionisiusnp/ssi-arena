<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TopicService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Topic $topic)
    {
        $this->model = $topic;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('topics.id as id'),
                DB::raw('topics.title as text'),
            ])
            ->where('roadmaps.is_published', true)
            ->when(count($filters), function ($q) use ($filters) {
                $q->where($filters);
            })
            ->limit(10);
        return $data;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $isPublished = isset($filter['is_published']) ? (filter_var($filter['is_published'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when(isset($isPublished), fn($query) => $query->where('is_published', $isPublished))
            ->orderByDesc('is_published')
            ->orderBy('sequence')
            ->paginate($perPage);
    }

    public function store(array $data, $auth)
    {
        try {
            $data['changed_by'] = $auth->id;
            return $this->model->create($data);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, $auth, Topic $topic)
    {
        try {
            $data['changed_by'] = $auth->id;
            $topic->update($data);
            return $topic;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive($auth, Topic $topic): bool
    {
        try {
            $topic->is_published = !$topic->is_published;
            $topic->changed_by = $auth->id ?? null;
            $topic->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Topic $topic): bool
    {
        try {
            return $topic->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
