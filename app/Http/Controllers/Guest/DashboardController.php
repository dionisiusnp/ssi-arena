<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public $scheduleService;
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }
    public function index(Request $request)
    {
        $auth = Auth::user();
        $filters = [
            'search' => $request->query('q') ?? null,
            'year' => $request->query('year') ?? now()->year,
        ];
        $schedules = $this->scheduleService->paginateMember($filters);
        $availableYears = $this->scheduleService->getAvailableYears();
        return view('guest.acara.index', compact('schedules', 'availableYears'));
    }
}
