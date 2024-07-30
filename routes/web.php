<?php

use App\Models\Master;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\FootballController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SwimmingController;
use App\Http\Controllers\BadmintonController;

Route::get('/', function () {
    return view('index', [
        'masters' => Master::filter(request(['search']))->latest()->paginate(5)->withQueryString(),
        'search' => request('search')
    ]);
})->middleware('auth');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);
Route::post('/logout', [LoginController::class, 'logout']);

Route::resource('/dashboard', MasterController::class)
    ->parameters([
        'dashboard' => 'master'
    ])->middleware('auth');

Route::resource('/dashboard/swimming', SwimmingController::class);
Route::resource('/dashboard/football', FootballController::class);
Route::resource('/dashboard/badminton', BadmintonController::class);

Route::resource('/files', FileController::class)->middleware('auth');


