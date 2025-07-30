<?php

use App\Http\Controllers\Admin\ActivityChecklistController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\CodeBlockController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\QuestDetailController;
use App\Http\Controllers\Admin\QuestLevelController;
use App\Http\Controllers\Admin\QuestTypeController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CodeBlockController as AdminCodeBlockController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Member\LeaderboardController as MemberLeaderboardController;
use App\Http\Controllers\Member\QuestController;
use App\Http\Controllers\Member\LessonController as MemberLessonController;
use App\Http\Controllers\Member\MemberController;
use App\Http\Controllers\Member\ActivityController as MemberActivityController;
use App\Http\Controllers\Guest\GuideController;
use App\Http\Controllers\Guest\DashboardController as GuestDashboardController;
use App\Http\Controllers\Guest\LeaderboardController as GuestLeaderboardController;
use App\Http\Controllers\Guest\LessonController as GuestLessonController;
use App\Http\Controllers\Guest\RegisterController;
use App\Http\Middleware\EnsureUserIsLecturer;
use App\Http\Middleware\EnsureUserIsNotMember;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

@include('select2.php');

// GUEST
Route::prefix('guest')->name('guest.')->group(function () {
    Route::get('/wiki',      [GuideController::class, 'index'])->name('wiki');
    Route::get('/schedule',[GuestDashboardController::class, 'index'])->name('schedule');
    Route::get('/leaderboard',[GuestLeaderboardController::class, 'index'])->name('leaderboard');
    Route::get('/lesson',[GuestLessonController::class, 'index'])->name('lesson');
    Route::get('/lesson/{lesson}',[GuestLessonController::class, 'show'])->name('lesson.show');
    Route::get('/register',[RegisterController::class, 'index'])->name('register');
    Route::post('/register/store',[RegisterController::class, 'register'])->middleware('throttle:register')->name('register.store');
});

// MEMBER
Route::middleware(['auth'])->prefix('member')->name('member.')->group(function () {
        Route::get('/lesson',[MemberLessonController::class, 'index'])->name('lesson');
        Route::get('/lesson/{lesson}',[MemberLessonController::class, 'show'])->name('lesson.show');
        
        Route::get('/quest',         [QuestController::class, 'index'])->name('quest');
        Route::get('/quest/{id}/claim', [QuestController::class, 'claim'])->name('quest.claim');

        Route::get('/leaderboard',[MemberLeaderboardController::class, 'index'])->name('leaderboard');
        
        Route::get('/profile',[MemberController::class, 'index'])->name('profile');
        Route::get('/reset',[MemberController::class, 'reset'])->name('reset');
        Route::put('/reset', [MemberController::class, 'updatePassword'])->name('reset.password');
        Route::get('/edit',[MemberController::class, 'edit'])->name('edit');
        Route::put('/update/{user}/member', [MemberController::class, 'update'])->name('update');


        Route::get('/activity',[MemberActivityController::class, 'index'])->name('activity');
        Route::get('/activity/{activity}/checklists', [MemberActivityController::class, 'checklists'])->name('activity.checklist');
        Route::put('/activity/{activity}/update',[MemberActivityController::class, 'update'])->name('activity.update');

        Route::get('/schedule',      [MemberDashboardController::class, 'index'])->name('schedule');
});

Route::get('/syntax/{syntax}', [AdminCodeBlockController::class,'show'])->name('syntax.show');

// LECTURER
Route::middleware(['auth', EnsureUserIsLecturer::class])->group(function () {
    Route::get('/admin-panel',[AdminDashboardController::class,'index'])->name('admin-panel');
    Route::resource('lesson', AdminLessonController::class);
    Route::resource('topic', TopicController::class);
    Route::resource('syntax', AdminCodeBlockController::class);
    Route::get('/code/list', [AdminCodeBlockController::class, 'list'])->name('code.list');
});

// ADMIN
Route::middleware(['auth', EnsureUserIsLecturer::class, EnsureUserIsNotMember::class])->group(function () {
    Route::get('/schedule/{schedule}/status',[ScheduleController::class,'toggleStatus'])->name('schedule.status');
    Route::resource('schedule', ScheduleController::class);

    Route::resource('season', SeasonController::class);
    Route::get('/quest-type/{quest_type}/status',[QuestTypeController::class,'toggleStatus'])->name('quest-type.status');
    Route::resource('quest-type', QuestTypeController::class);
    Route::get('/quest-level/{quest_level}/status',[QuestLevelController::class,'toggleStatus'])->name('quest-level.status');
    Route::resource('quest-level', QuestLevelController::class);
    Route::get('/quest-detail/{quest_detail}/status',[QuestDetailController::class,'toggleStatus'])->name('quest-detail.status');
    Route::resource('quest-detail', QuestDetailController::class);

    Route::get('/settings/level',[SettingController::class,'indexLevel'])->name('settings.level');
    Route::get('/settings/rank',[SettingController::class,'indexRank'])->name('settings.rank');
    Route::get('/settings/static',[SettingController::class,'indexStatic'])->name('settings.static');
    Route::get('/settings/dynamic',[SettingController::class,'indexDynamic'])->name('settings.dynamic');
    Route::get('/settings/general',[SettingController::class,'indexGeneral'])->name('settings.general');
    Route::post('/settings/store',[SettingController::class,'store'])->name('settings.update');

    Route::get('/user/{user}/status',[UserController::class,'toggleStatus'])->name('user.status');
    Route::resource('user', UserController::class);
    Route::resource('activity', AdminActivityController::class);
    Route::put('/activity/{activity}/point-plus',[AdminActivityController::class, 'pointPlus'])->name('activity.point.plus');
    Route::put('/activity/{activity}/point-minus',[AdminActivityController::class, 'pointMinus'])->name('activity.point.minus');
    Route::get('/activity-checklist/{activity_checklist}/status', [ActivityChecklistController::class, 'toggleStatus'])->name('activity-checklist.status');
});

require __DIR__.'/auth.php';
