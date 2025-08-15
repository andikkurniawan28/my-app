<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/data', [DashboardController::class, 'data'])->name('dashboard.data');
Route::get('/neracaBulanLaluDanBulanIni', [DashboardController::class, 'neracaBulanLaluDanBulanIni'])->name('dashboard.neracaBulanLaluDanBulanIni');
Route::get('/neracaSampaiDenganBulanLaluDanBulanIni', [DashboardController::class, 'neracaSampaiDenganBulanLaluDanBulanIni'])->name('dashboard.neracaSampaiDenganBulanLaluDanBulanIni');
Route::get('/pendapatanBebanBulanIni', [DashboardController::class, 'pendapatanBebanBulanIni'])->name('dashboard.pendapatanBebanBulanIni');

// Keuangan
Route::resource('accounts', AccountController::class);
Route::resource('journals', JournalController::class);

// Jadwal
Route::resource('schedules', ScheduleController::class);

// Pekerjaan
Route::resource('projects', ProjectController::class);
Route::resource('tasks', TaskController::class);

