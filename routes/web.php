<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicalRecordController;

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

// Auth
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginProcess'])->name('login_process');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index')->middleware(['auth']);
Route::get('/neracaBulanLaluDanBulanIni', [DashboardController::class, 'neracaBulanLaluDanBulanIni'])->name('dashboard.neracaBulanLaluDanBulanIni');
Route::get('/neracaSampaiDenganBulanLaluDanBulanIni', [DashboardController::class, 'neracaSampaiDenganBulanLaluDanBulanIni'])->name('dashboard.neracaSampaiDenganBulanLaluDanBulanIni');
Route::get('/pendapatanBebanBulanIni', [DashboardController::class, 'pendapatanBebanBulanIni'])->name('dashboard.pendapatanBebanBulanIni');
Route::get('/jadwalMenunggu', [DashboardController::class, 'jadwalMenunggu'])->name('dashboard.jadwalMenunggu');
Route::get('/proyekBelumDimulai', [DashboardController::class, 'proyekBelumDimulai'])->name('dashboard.proyekBelumDimulai');
Route::get('/tugasBelumSelesai', [DashboardController::class, 'tugasBelumSelesai'])->name('dashboard.tugasBelumSelesai');
Route::post('/selesaikanTugas', [DashboardController::class, 'selesaikanTugas'])->name('dashboard.selesaikanTugas');

// Keuangan
Route::resource('accounts', AccountController::class)->middleware(['auth']);
Route::resource('journals', JournalController::class)->middleware(['auth']);

// Jadwal
Route::resource('schedules', ScheduleController::class)->middleware(['auth']);

// Pekerjaan
Route::resource('projects', ProjectController::class)->middleware(['auth']);
Route::resource('tasks', TaskController::class)->middleware(['auth']);

// Penyimpanan
Route::resource('items', ItemController::class)->middleware(['auth']);

// Kesehatan
Route::resource('medicalRecords', MedicalRecordController::class)->middleware(['auth']);
