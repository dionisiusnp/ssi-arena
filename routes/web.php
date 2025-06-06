<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestTypeController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin-panel',[AdminDashboardController::class,'index'])->name('admin-panel');

    Route::resource('season', SeasonController::class);
    Route::resource('quest-type', QuestTypeController::class);

    Route::get('/member',[MemberDashboardController::class,'index'])->name('member');
});

require __DIR__.'/auth.php';
