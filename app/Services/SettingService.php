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

    public function getSettings($groupEnum)
    {
        $settings = $this->model->where('group', $groupEnum)->orderBy('sequence')->get();
        return $settings;
    }
}
