<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\ReportsController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->middleware('auth')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('auth')->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout.post');
Route::get('/dashboard', [ActivitiesController::class, 'dashboard'])->middleware('auth')->name('dashboard');
Route::get('/activities', [ActivitiesController::class, 'index'])->middleware('auth')->name('activities.index');
Route::get('/activities/create', [ActivitiesController::class, 'create'])->middleware('auth')->name('activities.create');
Route::post('/activities', [ActivitiesController::class, 'store'])->middleware('auth')->name('activities.store');
Route::get('/activities/{id}/edit', [ActivitiesController::class, 'edit'])->middleware('auth')->name('activities.edit');
Route::get('/activities/{id}/update', [ActivitiesController::class, 'showUpdateForm'])->middleware('auth')->name('activities.update');
Route::match(['patch', 'put'], '/activities/{id}/update', [ActivitiesController::class, 'update'])->middleware('auth')->name('activities.update.patch');
Route::get('/reports', [ReportsController::class, 'index'])->middleware('auth')->name('reports');