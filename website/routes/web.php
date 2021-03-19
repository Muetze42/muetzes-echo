<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/auth/callback', [AuthController::class, 'callback'])->name('auth.callback');
Route::get('/bot/auth/{token}', [AuthController::class, 'bot_auth'])->name('auth.bot');
Route::get('/user/auth', [AuthController::class, 'auth'])->name('auth.user');
