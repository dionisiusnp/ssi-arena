<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class SettingService
{
    private Model $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Setting $setting)
    {
        $this->model = $setting;
    }

    public function model()
    {
        return $this->model;
    }

    public function getSettings($groupEnum, $search = null)
    {
        $query = $this->model->where('group', $groupEnum);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('sequence')->get();
    }
}
