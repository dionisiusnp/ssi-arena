<?php

namespace App\Services;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

    public function paginateMember(array $filter = [], int $perPage = 9)
    {
        $search = $filter['search'] ?? null;
    $isActive = isset($filter['is_active']) ? (filter_var($filter['is_active'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
    $year = $filter['year'] ?? null;

    $today = Carbon::today()->toDateString();

    return $this->model
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        })
        ->when(isset($isActive), fn($query) => $query->where('is_active', $isActive))
        ->when($year, function ($query) use ($year) {
            $query->whereYear('started_at', $year);
        })
        ->orderByRaw("
            CASE 
                WHEN '$today' BETWEEN started_at AND finished_at THEN 0
                WHEN started_at > '$today' THEN 1
                ELSE 2
            END
        ")
        ->orderBy('started_at')
        ->paginate($perPage);
    }

    public function getAvailableYears()
    {
        return $this->model
            ->selectRaw('YEAR(started_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');
    }

    public function store(array $data, $auth)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $schedule = $this->model->create($data);

            DB::commit();
            return $schedule;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, $auth, Schedule $schedule)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $schedule->update($data);
            DB::commit();
            return $schedule;
        } catch (\Throwable $th) {
            DB::rollBack();
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
