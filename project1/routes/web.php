<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddController;
use App\Http\Controllers\SuperheroController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add', [AddController::class, 'index'])->name('add');
Route::post('/add', [AddController::class, 'calculate'])->name('add.calculate');

Route::get('/superheroes', [SuperheroController::class, 'index']);