<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QuestLevelService;
use App\Services\QuestTypeService;
use App\Services\SeasonService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class Select2Controller extends Controller
{
    public $seasonService, $questTypeService, $questLevelService;

    public function __construct(SeasonService $seasonService, QuestTypeService $questTypeService, QuestLevelService $questLevelService)
    {
        $this->seasonService = $seasonService;
        $this->questTypeService = $questTypeService;
        $this->questLevelService = $questLevelService;
    }

    public function select2Season(Request $request)
    {
        if ($request->wantsJson()) {
            $term    = trim($request->q);
            $filters = $request->filters ?? [];
            $query   = $this->seasonService->select2($filters);
            if (!empty($term)) {
                $query->where('name', 'like', '%' . $term . '%');
            }
            $data = $query->get();
            return Response::json($data);
        }
    }
}
