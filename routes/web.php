<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestDetailController;
use App\Http\Controllers\Admin\QuestLevelController;
use App\Http\Controllers\Admin\QuestTypeController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

@include('select2.php');

Route::middleware('auth')->group(function () {
    Route::get('/admin-panel',[AdminDashboardController::class,'index'])->name('admin-panel');

    Route::resource('season', SeasonController::class);
    Route::resource('quest-type', QuestTypeController::class);
    Route::resource('quest-level', QuestLevelController::class);
    Route::resource('quest-detail', QuestDetailController::class);

    Route::get('/user/{user}/status',[UserController::class,'toggleStatus'])->name('user.status');
    Route::resource('user', UserController::class);
    Route::resource('activity', ActivityController::class);

    Route::get('/member',[MemberDashboardController::class,'index'])->name('member');
});

require __DIR__.'/auth.php';
