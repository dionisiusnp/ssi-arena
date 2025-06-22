<?php

use App\Http\Controllers\Admin\ActivityChecklistController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\QuestDetailController;
use App\Http\Controllers\Admin\QuestLevelController;
use App\Http\Controllers\Admin\QuestTypeController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\LeaderboardController;
use App\Http\Controllers\Member\QuestController;
use App\Http\Controllers\Member\LessonController as MemberLessonController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

@include('select2.php');

Route::prefix('guest')->name('guest.')->group(function () {
    Route::get('/lesson',        [MemberLessonController::class, 'index'])->name('lesson');
    Route::get('/schedule',      [MemberDashboardController::class, 'index'])->name('schedule');
});
Route::middleware('auth')->group(function () {
    // ADMIN
    Route::get('/admin-panel',[AdminDashboardController::class,'index'])->name('admin-panel');

    Route::get('/schedule/{schedule}/status',[ScheduleController::class,'toggleStatus'])->name('schedule.status');
    Route::resource('schedule', ScheduleController::class);

    Route::resource('lesson', AdminLessonController::class);
    Route::resource('topic', TopicController::class);

    Route::resource('season', SeasonController::class);
    Route::get('/quest-type/{quest_type}/status',[QuestTypeController::class,'toggleStatus'])->name('quest-type.status');
    Route::resource('quest-type', QuestTypeController::class);
    Route::get('/quest-level/{quest_level}/status',[QuestLevelController::class,'toggleStatus'])->name('quest-level.status');
    Route::resource('quest-level', QuestLevelController::class);
    Route::get('/quest-detail/{quest_detail}/status',[QuestDetailController::class,'toggleStatus'])->name('quest-detail.status');
    Route::resource('quest-detail', QuestDetailController::class);

    Route::get('/user/{user}/status',[UserController::class,'toggleStatus'])->name('user.status');
    Route::resource('user', UserController::class);
    Route::resource('activity', ActivityController::class);
    Route::get('/activity-checklist/{activity_checklist}/status', [ActivityChecklistController::class, 'toggleStatus'])->name('activity-checklist.status');

    // MEMBER
    Route::prefix('member')->name('member.')->group(function () {
        Route::get('/lesson',        [MemberLessonController::class, 'index'])->name('lesson');
        Route::get('/lesson/{lesson}',        [MemberLessonController::class, 'show'])->name('lesson.show');
        
        Route::get('/leaderboard',   [LeaderboardController::class, 'index'])->name('leaderboard');
        Route::get('/profile',       [MemberController::class, 'index'])->name('profile');
        
        Route::get('/quest',         [QuestController::class, 'index'])->name('quest');
        Route::get('/quest/{id}/claim', [QuestController::class, 'claim'])->name('quest.claim');


        Route::get('/registration',  [RegistrationController::class, 'index'])->name('registration');
        Route::get('/schedule',      [MemberDashboardController::class, 'index'])->name('schedule');
    });
});

require __DIR__.'/auth.php';
