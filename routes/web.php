<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\Logout2Controller;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\UserNewController;
use App\Http\Controllers\User\RegisterNewController;
use App\Http\Controllers\User\LoginNewController;
use App\Http\Controllers\User\RoomController;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [LoginNewController::class, 'login'])->name('users.login');
Route::post('/user-login', [LoginNewController::class, 'loginOnPage'])->name('login')->middleware('guest');

Route::prefix('/admin')->middleware('auth')->group(function(){
    Route::get('/users', [UserNewController::class, 'index'])->name('users.index');
    Route::get('/users/edit/{id}', [UserNewController::class, 'edit'])->name('user.edit');
    Route::post('/users/update/{id}', [UserNewController::class, 'update'])->name('user.update');
    Route::post('/users/destroy/{id}', [UserNewController::class, 'destroy'])->name('user.destroy');

    Route::get('/rooms/{id}', [RoomController::class, 'index'])->name('user.rooms');
    Route::get('/rooms/add/{id}', [RoomController::class, 'create'])->name('room.add');
    Route::get('/rooms/edit/{id}', [RoomController::class, 'edit'])->name('room.edit');
    Route::post('/rooms/update/{id}', [RoomController::class, 'update'])->name('room.update');
    Route::post('/rooms/store/{id}', [RoomController::class, 'store'])->name('room.store');
    Route::post('/rooms/destroy/{id}', [RoomController::class, 'destroy'])->name('room.destroy');

    Route::get('/register', [RegisterNewController::class, 'registerShow']);
    Route::post('/user-register', [RegisterNewController::class, 'register'])->name('register');
    
});

Route::prefix('/auth')->group(function(){
	// Authentication Routes...
	Route::post('/login', LoginController::class)->middleware('guest');
    Route::post('/logout', LogoutController::class);
    Route::post('/logout2', Logout2Controller::class)->name('logout2')->middleware('guest');
    Route::post('/register', RegisterController::class)->middleware('guest');
});
