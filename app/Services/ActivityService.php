<?php

namespace App\Services;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;

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
}
