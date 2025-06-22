<?php

namespace App\Services;

use App\Enums\VisibilityEnum;
use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class LessonService
{
    protected Model $model;
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
        $language = $filter['language'] ?? null;
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
            ->when($language, function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->when($visibility, function ($query) use ($visibility) {
                $query->where('visibility', $visibility);
            })
            ->withCount('topics')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function paginateMember(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $role = $filter['role'] ?? null;
        $language = $filter['language'] ?? null;

        return $this->model
            ->whereNotIn('visibility', [VisibilityEnum::DRAFT->value])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->when($role, function ($query) use ($role) {
                $query->where('role', $role);
            })
            ->when($language, function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->withCount('topics')
            ->orderByDesc('created_at')
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

    public function update(array $data, $auth, Lesson $lesson)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            if (!empty($data['visibility']) && $lesson->visibility !== $data['visibility']) {
                $topics = Topic::where('lesson_id', $lesson->id)->get();
                if ($topics->isNotEmpty() && !empty($data['visibility'])) {
                    foreach ($topics as $topic) {
                        $topic->update(['visibility' => $data['visibility']]);
                    }
                }
            }
            $lesson->update($data);
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
