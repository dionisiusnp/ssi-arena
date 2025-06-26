<?php

namespace App\Services;

use App\Enums\QuestEnum;
use App\Models\RewardLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RewardLogService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(RewardLog $rewardLog)
    {
        $this->model = $rewardLog;
    }

    public function model()
    {
        return $this->model;
    }

    public function listBySeasonAndPlayer($seasonId, $userId)
    {
        return $this->model
        ->where('season_id', $seasonId)
        ->where('user_id', $userId)
        ->orderByDesc('created_at')
        ->get();
    }

    public function store($questDetailId, $activityId, $seasonId, $userId, $pointTotal, $status)
    {
        DB::beginTransaction();
        try {
            //code...
            if ($status === QuestEnum::MINUS->value) {
                # code...
            } else if ($status === QuestEnum::PLUS->value) {
                # code...
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            //throw $th;
        }
    }
}
