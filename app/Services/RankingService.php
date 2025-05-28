<?php

namespace App\Services;

use App\Models\Ranking;

class RankingService
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Ranking $ranking)
    {
        $this->model;
    }

    public function model()
    {
        return $this->model;
    }
}
