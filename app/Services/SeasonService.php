<?php

namespace App\Services;

use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SeasonService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Season $season)
    {
        $this->model = $season;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('seasons.id as id'),
                DB::raw('seasons.name as text'),
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
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                });
            })
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

    public function lastSeason()
    {
        return $this->model
        ->where('started_at', '<=', Carbon::today())
        ->where('finished_at', '>=', Carbon::today())
        ->orderByDesc('started_at')
        ->first();
    }

    public function update(array $data, $auth, Season $season)
    {
        try {
            $data['changed_by'] = $auth->id;
            $season->update($data);
            return $season;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive($auth, Season $season): bool
    {
        try {
            $season->is_active = !$season->is_active;
            $season->changed_by = $auth->id ?? null;
            $season->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(Season $season): bool
    {
        try {
            return $season->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
