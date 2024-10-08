<?php

use App\Http\Controllers\AuthController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('verify-email/{code}', [AuthController::class, 'verifyEmail'])->name('verify-email');

Route::get('test-email', [AuthController::class, 'showEmailVerificationView']);

Route::post('/login', [AuthController::class, 'authenticate']);
