<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\ActivityChecklist;
use App\Models\QuestDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
        $playerId  = $filter['claimed_by'] ?? null;
        $seasonId  = $filter['season_id'] ?? null;
        $search    = $filter['search'] ?? null;

        return $this->model
            ->with([
                'checklists.questRequirement',
                'detail.season',
                'detail.questType',
                'detail.questLevel',
            ])
            ->when($playerId, fn($q) => $q->where('claimed_by', $playerId))
            ->when($seasonId, fn($q) => $q->whereHas('detail', fn($q) => $q->where('season_id', $seasonId)))
            ->when($search, fn($q) => $q->whereHas('detail', fn($q) => $q->where('name', 'like', '%' . $search . '%')))
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

    public function questClaim($questDetailId, $auth)
    {
        DB::beginTransaction();
        try {
            $questDetail = QuestDetail::with('requirements')->find($questDetailId);
            $questRequirements = $questDetail->requirements;
            $data['quest_detail_id'] = $questDetail->id;
            $data['claimed_by'] = $auth->id;
            $data['changed_by'] = $auth->id;
            $activity = $this->model->create($data);
            if ($questRequirements->isNotEmpty()) {
                foreach ($questRequirements as $requirement) {
                    ActivityChecklist::create([
                        'quest_requirement_id' => $requirement->id,
                        'activity_id' => $activity->id,
                        'changed_by' => $auth->id,
                    ]);
                }
            }
            DB::commit();
            return $activity;
        } catch (\Throwable $th) {
            DB::rollBack();
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

    public function isClear($auth, Activity $activity): bool
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
