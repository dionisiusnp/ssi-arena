<?php

namespace App\Services;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ScheduleService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Schedule $schedule)
    {
        $this->model = $schedule;
    }

    public function model()
    {
        return $this->model;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $isActive = isset($filter['is_active']) ? (filter_var($filter['is_active'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
            ->when(isset($isActive), fn($query) => $query->where('is_active', $isActive))
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

    public function update(array $data, $auth, Schedule $schedule)
    {
        try {
            $data['changed_by'] = $auth->id;
            $schedule->update($data);
            return $schedule;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive($auth, Schedule $schedule): bool
    {
        try {
            $schedule->is_active = !$schedule->is_active;
            $schedule->changed_by = $auth->id ?? null;
            $schedule->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Schedule $schedule): bool
    {
        try {
            return $schedule->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
