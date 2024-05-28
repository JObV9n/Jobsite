<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use Illuminate\Support\Facades\Cache;

Route::get('/', function () {
    return view('welcome');
});

//custom web routes
Route::get('dashboard', [CustomAuthController::class, 'dashboard']);
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('register', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

//
Route::get('forget-password',[CustomAuthController::class, 'showForgetPassword'])->name('forget.password.get');
Route::post('forget-password',[CustomAuthController::class, 'forgetPassword'])->name('forget.password.post');

Route::get('/cache', function () {
    return Cache::get('key');
});
