<?php

namespace App\Services;

use App\Enums\QuestEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('users.id as id'),
                DB::raw('users.name as text'),
            ])
            ->where('users.is_member', true)
            ->where('users.is_active', true)
            ->when(count($filters), function ($q) use ($filters) {
                $q->where($filters);
            })
            ->limit(10);
        return $data;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $isMember = isset($filter['is_member']) ? (filter_var($filter['is_member'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        $isLecturer = isset($filter['is_lecturer']) ? (filter_var($filter['is_lecturer'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        $isActive = isset($filter['is_active']) ? (filter_var($filter['is_active'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;

        return $this->model
            ->when($search, fn($query) =>
                $query->where(fn($q) =>
                    $q->where('name', 'LIKE', "%{$search}%")
                )
            )
            ->when(!is_null($isMember), fn($query) =>
                $query->where('is_member', $isMember)
            )
            ->when(!is_null($isLecturer), fn($query) =>
                $query->where('is_lecturer', $isLecturer)
            )
            ->when(!is_null($isActive), fn($query) =>
                $query->where('is_active', $isActive)
            )
            ->withCount(['activities as activities_count' => function ($query) {
                $query->where('status', 'testing');
            }])
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function paginateDashboard(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $seasonId = $filter['season_id'] ?? null;

        return $this->model
            ->where('is_member', true)
            // ->where('is_active', true)
            ->withCount(['activities as total_point' => function ($query) use ($seasonId) {
                $query->where('status', QuestEnum::PLUS->value)
                    ->whereColumn('activities.claimed_by', 'users.id')
                    ->join('quest_details', 'activities.quest_detail_id', '=', 'quest_details.id')
                    ->when(!is_null($seasonId), function ($q) use ($seasonId) {
                        $q->where('quest_details.season_id', $seasonId);
                    })
                    ->selectRaw('SUM(quest_details.point_total)');
            }])
            ->orderByDesc('total_point')
            ->paginate($perPage);
    }

    public function topScorePlayer()
    {
        return $this->model
        ->where('is_active', true)
        ->orderByDesc('current_point')
        ->first();
    }

    public function topScoreRanked()
    {
        return $this->model
        ->where('is_active', true)
        ->orderByDesc('season_point')
        ->first();
    }

    public function store(array $data)
    {
        try {
            $data['password'] = Hash::make($data['password']);
            return $this->model->create($data);
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, User $user)
    {
        try {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            $user->update($data);
            return $user;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive(User $user): bool
    {
        try {
            $user->is_active = !$user->is_active;
            $user->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(User $user): bool
    {
        try {
            return $user->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
