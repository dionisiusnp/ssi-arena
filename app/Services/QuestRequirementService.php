<?php

namespace App\Services;

use App\Models\QuestRequirement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class QuestRequirementService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(QuestRequirement $questRequirement)
    {
        $this->model = $questRequirement;
    }

    public function model()
    {
        return $this->model;
    }

    // public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    // {
    //     $search = $filter['search'] ?? null;
    //     $isEditable = isset($filter['is_editable']) ? (filter_var($filter['is_editable'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
    //     return $this->model
    //         ->when($search, function ($query) use ($search) {
    //             $query->where(function ($q) use ($search) {
    //                 $q->where('description', 'LIKE', "%{$search}%");
    //             });
    //         })
    //         ->when(isset($isEditable), fn($query) => $query->where('is_editable', $isEditable))
    //         ->orderByDesc('is_editable')
    //         ->paginate($perPage);
    // }

    public function byQuestDetail($questDetailId)
    {
        try {
            return $this->model->where('quest_detail_id','=',$questDetailId)->get();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function store($description, $questDetailId, $auth)
    {
        try {
            $data['quest_detail_id'] = $questDetailId;
            $data['description'] = $description;
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

    public function update(array $data, $auth, QuestRequirement $questRequirement)
    {
        try {
            $data['changed_by'] = $auth->id;
            $questRequirement->update($data);
            return $questRequirement;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(QuestRequirement $questRequirement): bool
    {
        try {
            return $questRequirement->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
