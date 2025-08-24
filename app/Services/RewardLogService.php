<?php

namespace App\Services;

use App\Enums\QuestEnum;
use App\Enums\SettingGroupEnum;
use App\Models\QuestDetail;
use App\Models\RewardLog;
use App\Models\Setting;
use App\Models\User;
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

    public function levelAndPoint($questDetailId, $activityId, $userId, $status)
    {
        DB::beginTransaction();
        try {
            $user = User::findOrFail($userId);
            $questDetail = QuestDetail::findOrFail($questDetailId);
            $getPoint = $questDetail->point_total;
            $seasonId = $questDetail->season_id;
            // 1. Prepare new point values
            $newCurrentPoint = $status === QuestEnum::PLUS->value
                ? $user->current_point + $getPoint
                : max(0, $user->current_point - $getPoint);
            $newSeasonPoint = $user->season_point;
            if ($seasonId) {
                $newSeasonPoint = $status === QuestEnum::PLUS->value
                    ? $user->season_point + $getPoint
                    : max(0, $user->season_point - $getPoint);
            }
            // 2. Determine new levels based on new points
            $newCurrentLevel = $user->current_level;
            $levelSetting = Setting::where('group', SettingGroupEnum::LEVEL->value)
                ->where('current_value', '<=', $newCurrentPoint)
                ->orderByRaw('CAST(current_value AS UNSIGNED) DESC')
                ->first();
            if ($levelSetting) {
                $newCurrentLevel = (int) str_replace('level_', '', $levelSetting->key);
            }
            $newSeasonLevel = $user->season_level;
            if ($seasonId) {
                $rankSetting = Setting::where('group', SettingGroupEnum::RANKED->value)
                    ->where('current_value', '<=', $newSeasonPoint)
                    ->orderByRaw('CAST(current_value AS UNSIGNED) DESC')
                    ->first();
                if ($rankSetting) {
                    $newSeasonLevel = (int) str_replace('rank_', '', $rankSetting->key);
                }
            }
            // 3. Consolidate all data for update
            $updatePayload = [
                'current_point' => $newCurrentPoint,
                'current_level' => $newCurrentLevel,
                'season_point'  => $newSeasonPoint,
                'season_level'  => $newSeasonLevel,
            ];
            // 4. Perform a single update on the user
            $user->update($updatePayload);
            // 5. Create a consistent log entry
            RewardLog::create([
                'season_id'       => $seasonId,
                'quest_detail_id' => $questDetailId,
                'activity_id'     => $activityId,
                'user_id'         => $userId,
                'get_point'       => $getPoint,
                'status'          => $status,
                'current_point'   => $updatePayload['current_point'],
                'current_level'   => $updatePayload['current_level'],
                'season_point'    => $updatePayload['season_point'],
                'season_level'    => $updatePayload['season_level'],
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
