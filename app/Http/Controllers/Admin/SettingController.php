<?php

namespace App\Http\Controllers\Admin;

use App\Enums\SettingGroupEnum;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }
    /**
     * Display a listing of the resource.
     */
    public function indexLevel(Request $request)
    {
        $search = $request->input('search');
        $settings = $this->settingService->getSettings(SettingGroupEnum::LEVEL->value, $search);

        return view('admin.pengaturan.level.index', compact('settings'));
    }

    public function indexStatic(Request $request)
    {
        $search = $request->input('search');
        $settings = $this->settingService->getSettings(SettingGroupEnum::PERKQUESTLEVEL->value, $search);
        return view('admin.pengaturan.keuntungan-statis.index', compact('settings'));
    }

    public function indexDynamic(Request $request)
    {
        $search = $request->input('search');
        $settings = $this->settingService->getSettings(SettingGroupEnum::PERKCUSTOM->value, $search);
        return view('admin.pengaturan.keuntungan-dinamis.index', compact('settings'));
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
        $data = $request->input('settings', []);

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update([
                'current_value' => $value
            ]);
        }
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
