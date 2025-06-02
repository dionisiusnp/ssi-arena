<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestDetail;
use App\Services\QuestDetailService;
use Illuminate\Http\Request;

class QuestDetailController extends Controller
{
    public $questDetailService;

    public function __construct(QuestDetailService $questDetailService)
    {
        $this->questDetailService = $questDetailService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestDetail $questDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(QuestDetail $questDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, QuestDetail $questDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(QuestDetail $questDetail)
    {
        //
    }
}
