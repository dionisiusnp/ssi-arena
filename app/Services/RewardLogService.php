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

            // POINT: current_point (global)
            $newPoint = $status === QuestEnum::PLUS->value
                ? $user->current_point + $getPoint
                : max(0, $user->current_point - $getPoint);

            $updateData = ['current_point' => $newPoint];

            $level = Setting::where('group', SettingGroupEnum::LEVEL->value)
                ->where('current_value', '<=', $newPoint)
                ->orderByDesc('current_value')
                ->first();

            if ($level) {
                $updateData['current_level'] = (int) str_replace('level_', '', $level->key);
            }

            // POINT: season_point (jika ada season)
            $updateRankData = [];
            $levelRankNumber = $user->season_level;
            $newRankPoint = $user->season_point;

            if ($seasonId) {
                $newRankPoint = $status === QuestEnum::PLUS->value
                    ? $user->season_point + $getPoint
                    : max(0, $user->season_point - $getPoint);

                $updateRankData['season_point'] = $newRankPoint;

                $rank = Setting::where('group', SettingGroupEnum::RANKED->value)
                    ->where('current_value', '<=', $newRankPoint)
                    ->orderByDesc('current_value')
                    ->first();

                if ($rank) {
                    $levelRankNumber = (int) str_replace('rank_', '', $rank->key);
                    $updateRankData['season_level'] = $levelRankNumber;
                }
            }

            // Gabungkan semua update dan simpan hanya sekali
            $user->update(array_merge($updateData, $updateRankData));

            // Simpan log
            RewardLog::create([
                'season_id'       => $seasonId,
                'quest_detail_id' => $questDetailId,
                'activity_id'     => $activityId,
                'user_id'         => $userId,
                'season_level'    => $updateRankData['season_level'] ?? $user->season_level,
                'season_point'    => $newRankPoint,
                'current_level'   => $updateData['current_level'] ?? $user->current_level,
                'current_point'   => $newPoint,
                'get_point'       => $getPoint,
                'status'          => $status,
            ]);

            DB::commit();

        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
