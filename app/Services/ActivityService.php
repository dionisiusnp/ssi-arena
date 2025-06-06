<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Activity $activity)
    {
        $this->model = $activity;
    }

    public function model()
    {
        return $this->model;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $status = isset($filter['status']) ? (filter_var($filter['status'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        return $this->model
            // ->when($search, function ($query) use ($search) {
            //     $query->where(function ($q) use ($search) {
            //         $q->where('title', 'LIKE', "%{$search}%")
            //         ->orWhere('description', 'LIKE', "%{$search}%");
            //     });
            // })
            ->when(isset($status), fn($query) => $query->where('status', $status))
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

    public function update(array $data, $auth, Activity $activity)
    {
        try {
            $data['changed_by'] = $auth->id;
            $activity->update($data);
            return $activity;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive($auth, Activity $activity): bool
    {
        try {
            $activity->status = !$activity->status;
            $activity->changed_by = $auth->id ?? null;
            $activity->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Activity $activity): bool
    {
        try {
            return $activity->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
