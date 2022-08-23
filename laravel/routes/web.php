<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    session([
        'random_laravel_value' => \Illuminate\Support\Str::random(16)
    ]);

    return response()->json([
        'random_value_in_sesson' => session('random_value'),
        'random_value_by_laravel' => session('random_laravel_value'),
        'session_id' => session()->getId(),
    ]);
});
