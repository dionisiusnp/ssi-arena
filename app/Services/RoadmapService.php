<?php

namespace App\Services;

use App\Models\Roadmap;

class RoadmapService
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Roadmap $roadmap)
    {
        $this->model = $roadmap;
    }

    public function model()
    {
        return $this->model;
    }
}
