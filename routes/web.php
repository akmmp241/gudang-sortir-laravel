<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'home'])
    ->middleware('must.not.login');

Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware('must.login');

Route::get('/users/login', [UserController::class, 'login'])
    ->middleware(['must.not.login']);
Route::post('/users/login', [UserController::class, 'postLogin'])
    ->middleware(['must.not.login']);
Route::get('/users/register', [UserController::class, 'register'])
    ->middleware(['must.not.login']);
Route::post('/users/register', [UserController::class, 'postRegister'])
    ->middleware(['must.not.login']);
Route::get('/users/update-password', [UserController::class, 'updatePassword'])
    ->middleware('must.login');
Route::post('/users/update-password', [UserController::class, 'postUpdatePassword'])
    ->middleware('must.login');
Route::get('/users/logout', [UserController::class, 'logout'])
    ->middleware('must.login');

Route::get('/welcome', function () {
    return view('welcome');
});
