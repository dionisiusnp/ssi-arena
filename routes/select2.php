<?php

use App\Http\Controllers\Admin\Select2Controller;
use Illuminate\Support\Facades\Route;

Route::get('/season/select2', [Select2Controller::class, 'select2Season'])->name('season.select2');
Route::get('/type/select2', [Select2Controller::class, 'select2Type'])->name('quest-type.select2');
Route::get('/level/select2', [Select2Controller::class, 'select2Level'])->name('quest-level.select2');
Route::get('/player/select2', [Select2Controller::class, 'select2Player'])->name('player.select2');