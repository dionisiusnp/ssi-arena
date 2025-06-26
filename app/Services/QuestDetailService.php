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
    public $questRequirementService, $activityService;
    /**
     * Create a new class instance.
     */
    public function __construct(QuestDetail $questDetail, QuestRequirementService $questRequirementService, ActivityService $activityService)
    {
        $this->model = $questDetail;
        $this->questRequirementService = $questRequirementService;
        $this->activityService = $activityService;
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
            ->with('requirements')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when(isset($isEditable), fn($query) => $query->where('is_editable', $isEditable))
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function paginateMember(array $filter = [], int $perPage = 10, $auth): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $userId = $auth->id;
        return $this->model
            ->with('requirements')
            ->where('is_editable', false)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when($userId, function ($query) use ($userId) {
                $query->whereDoesntHave('activities', function ($q) use ($userId) {
                    $q->where('claimed_by', $userId);
                });
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function store(array $data, array $reqs, $auth)
    {
        try {
            $data['changed_by'] = $auth->id;
            $data['claimable_by'] = isset($data['claimable_by']) && $data['claimable_by'] ? json_encode($data['claimable_by']) : null;
            $data['claimable_clan_by'] = isset($data['claimable_clan_by']) && $data['claimable_clan_by'] ? json_encode($data['claimable_by']) : null;
            $data['point_total'] = $data['point'] + $data['point_additional'];
            $qd = $this->model->create($data);
            if (!empty($reqs)) {
                foreach($reqs as $item){
                    if (!empty(trim($item['description'] ?? ''))) {
                        $this->questRequirementService->store($item['description'], $qd->id, $auth);
                    }
                }
            }
            return $qd;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, array $reqs, $auth, QuestDetail $questDetail)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $data['claimable_by'] = isset($data['claimable_by']) && $data['claimable_by'] ? json_encode($data['claimable_by']) : null;
            $data['point_total'] = $data['point'] + $data['point_additional'];
            $qd = $questDetail->update($data);
            if (!empty($reqs)) {
                foreach ($questDetail->activities as $activity) {
                    $activity->checklists()->delete();
                }
                $questDetail->activities()->delete();
                $existingRequirements = $this->questRequirementService->byQuestDetail($questDetail->id);
                if ($existingRequirements->isNotEmpty()) {
                    $existingRequirements->each->delete();
                }

                foreach($reqs as $item){
                    if (!empty(trim($item['description'] ?? ''))) {
                        $this->questRequirementService->store($item['description'], $questDetail->id, $auth);
                    }
                }
            }
            DB::commit();
            return $qd;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isEditable($auth, QuestDetail $questDetail): bool
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
