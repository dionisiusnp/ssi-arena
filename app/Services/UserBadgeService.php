<?php

namespace App\Services;

use App\Models\UserBadge;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class UserBadgeService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(UserBadge $userBadge)
    {
        $this->model = $userBadge;
    }

    public function model()
    {
        return $this->model;
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

    public function update(array $data, $auth, UserBadge $userBadge)
    {
        try {
            $data['changed_by'] = $auth->id;
            $userBadge->update($data);
            return $userBadge;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive($auth, UserBadge $userBadge): bool
    {
        try {
            $userBadge->is_active = !$userBadge->is_active;
            $userBadge->changed_by = $auth->id ?? null;
            $userBadge->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(UserBadge $userBadge): bool
    {
        try {
            return $userBadge->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
