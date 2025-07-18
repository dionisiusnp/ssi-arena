<?php

namespace App\Services;

use App\Enums\QuestEnum;
use App\Models\ActivityChecklist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityChecklistService
{
    protected Model $model;
    public $activityService;
    /**
     * Create a new class instance.
     */
    public function __construct(ActivityChecklist $activityChecklist, ActivityService $activityService)
    {
        $this->model = $activityChecklist;
        $this->activityService = $activityService;
    }

    public function model()
    {
        return $this->model;
    }

    public function byActivity($activityId)
    {
        try {
            return $this->model->where('activity_id','=',$activityId)->get();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
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

    public function update(array $data, $auth, ActivityChecklist $activityChecklist)
    {
        try {
            $data['changed_by'] = $auth->id;
            $activityChecklist->update($data);
            return $activityChecklist;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function isClear($auth, ActivityChecklist $activityChecklist): bool
    {
        try {
            $activity = $this->activityService->model()->find($activityChecklist->activity_id);
            $activity->update([
                'status' => QuestEnum::PENDING->value,
            ]);
            $activityChecklist->is_clear = !$activityChecklist->is_clear;
            $activityChecklist->changed_by = $auth->id ?? null;
            $activityChecklist->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(ActivityChecklist $activityChecklist): bool
    {
        try {
            return $activityChecklist->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
