<?php

namespace App\Services;

use App\Models\Season;

class SeasonService
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Season $season)
    {
        $this->model = $season;
    }

    public function model()
    {
        return $this->model;
    }
}
