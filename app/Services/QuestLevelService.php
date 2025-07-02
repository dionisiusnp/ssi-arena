<?php

namespace App\Services;

use App\Enums\FieldTypeEnum;
use App\Enums\SettingGroupEnum;
use App\Models\QuestLevel;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class QuestLevelService
{
    protected Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(QuestLevel $questLevel)
    {
        $this->model = $questLevel;
    }

    public function model()
    {
        return $this->model;
    }

    public function select2($filters = []): Builder
    {
        $data = $this->model->select([
                DB::raw('quest_levels.id as id'),
                DB::raw('quest_levels.name as text'),
            ])
            ->where('quest_levels.is_active', true)
            ->when(count($filters), function ($q) use ($filters) {
                $q->where($filters);
            })
            ->limit(10);
        return $data;
    }

    public function paginate(array $filter = [], int $perPage = 10): LengthAwarePaginator
    {
        $search = $filter['search'] ?? null;
        $isActive = isset($filter['is_active']) ? (filter_var($filter['is_active'], FILTER_VALIDATE_BOOLEAN) ? 1 : 0) : null;
        return $this->model
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
                });
            })
            ->when(isset($isActive), fn($query) => $query->where('is_active', $isActive))
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function store(array $data, $auth)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $ql = $this->model->create($data);

            $slCount = Setting::where('group', SettingGroupEnum::PERKQUESTLEVEL->value)->count();

            Setting::create([
                'group' => SettingGroupEnum::PERKQUESTLEVEL->value,
                'sequence' => $slCount+1,
                'key' => 'ql_'.$ql->id,
                'name' => 'Level Tantangan ' . $ql->name,
                'description' => 'Nilai level minimal untuk Level Tantangan ' . $ql->name,
                'column_type' => FieldTypeEnum::INTEGER->value,
                'default_value' => 0,
                'current_value' => 0, // dari level
                'changed_by' => $auth->id,
            ]); 

            DB::commit();
            return $ql;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }

    public function show(int $id)
    {
        return $this->model->find($id);
    }

    public function update(array $data, $auth, QuestLevel $questLevel)
    {
        DB::beginTransaction();
        try {
            $data['changed_by'] = $auth->id;
            $nameChanged = isset($data['name']) && $data['name'] !== $questLevel->name;
            $questLevel->update($data);
            if ($nameChanged) {
                $settingKey = 'ql_' . $questLevel->id;
                $setting = Setting::where('key', $settingKey)
                    ->where('group', SettingGroupEnum::PERKQUESTLEVEL->value)
                    ->first();
                if ($setting) {
                    $setting->update([
                        'name' => 'Level Tantangan ' . $questLevel->name,
                        'description' => 'Nilai level minimal untuk Level Tantangan ' . $questLevel->name,
                        'changed_by' => $auth->id,
                    ]);
                }
            }
            DB::commit();
            return $questLevel;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \ErrorException($th->getMessage());
        }
    }


    public function isActive($auth, QuestLevel $questLevel): bool
    {
        try {
            $questLevel->is_active = !$questLevel->is_active;
            $questLevel->changed_by = $auth->id ?? null;
            $questLevel->save();
            return true;
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }

    public function destroy(QuestLevel $questLevel): bool
    {
        try {
            return $questLevel->delete();
        } catch (\Throwable $th) {
            throw new \ErrorException($th->getMessage());
        }
    }
}
