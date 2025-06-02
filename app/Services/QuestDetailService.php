<?php

namespace App\Services;

use App\Models\QuestDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuestDetailService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(QuestDetail $questDetail)
    {
        $this->model = $questDetail;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('quest_details.id as id'),
                DB::raw('quest_details.name as text'),
            ])
            ->where('quest_details.is_editable', false)
            ->when(count($filters), function ($q) use ($filters) {
                $q->where($filters);
            })
            ->limit(10);
        return $data;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $isEditable = isset($filter['is_editable']) ? (filter_var($filter['is_editable'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when(isset($isEditable), fn($query) => $query->where('is_editable', $isEditable))
            ->orderByDesc('is_editable')
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

    public function update(array $data, $auth, QuestDetail $questDetail)
    {
        try {
            $data['changed_by'] = $auth->id;
            $questDetail->update($data);
            return $questDetail;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isActive($auth, QuestDetail $questDetail): bool
    {
        try {
            $questDetail->is_editable = !$questDetail->is_editable;
            $questDetail->changed_by = $auth->id ?? null;
            $questDetail->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(QuestDetail $questDetail): bool
    {
        try {
            return $questDetail->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
