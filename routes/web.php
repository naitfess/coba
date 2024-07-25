<?php

use App\Models\Master;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\FootballController;
use App\Http\Controllers\SwimmingController;
use App\Http\Controllers\BadmintonController;

Route::get('/', function () {
    return view('index', [
        'masters' => Master::filter(request(['search']))->latest()->paginate(5)->withQueryString(),
        'search' => request('search')
    ]);
});

Route::resource('/dashboard', MasterController::class)
    ->parameters([
        'dashboard' => 'master'
    ]);
Route::resource('/dashboard/swimming', SwimmingController::class);
Route::resource('/dashboard/football', FootballController::class);
Route::resource('/dashboard/badminton', BadmintonController::class);


'tes coba4';

'coba3';

