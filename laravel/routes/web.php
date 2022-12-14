<?php

use Illuminate\Http\Request;
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

Route::get('/', function (Request $request) {
    if (!session('random_laravel_value', false))
        session([
            'random_laravel_value' => \Illuminate\Support\Str::random(16)
        ]);

    return response()->json([
        'cookies' => $request->cookie(),
        'date_time' => date('Y-m-d H:i:s'),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
    ]);
});
