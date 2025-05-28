<?php

namespace App\Services;

use App\Models\Challenge;

class ChallengeService
{
    protected $model;
    /**
     * Create a new class instance.
     */
    public function __construct(Challenge $challenge)
    {
        $this->model = $challenge;
    }

    public function model()
    {
        return $this->model;
    }
}
