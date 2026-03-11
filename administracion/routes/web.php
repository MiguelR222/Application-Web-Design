<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController; 
use App\Http\Controllers\ReportesController; 
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['auth'])->group(function () { 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard'); 
Route::get('/admin', [AdminController::class, 'index']) ->middleware('role:admin') ->name('admin'); 
Route::get('/reportes', [ReportesController::class, 'index']) ->middleware('role:gerente') ->name('reportes'); 
});

require __DIR__.'/auth.php';
