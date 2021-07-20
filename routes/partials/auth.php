<?php

use App\Http\Controllers\Auth\{
	LoginController,
	ForgotPasswordController
};

Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'signin'])->name('signin');
Route::post('complete-login', [LoginController::class, 'completeLogin']);
Route::get('esqueci-a-senha', [ForgotPasswordController::class, 'index']);
Route::post('esqueci-a-senha', [ForgotPasswordController::class, 'resetPassword']);
Route::get('esqueci-a-senha/{token}', [ForgotPasswordController::class, 'renewPassword']);
Route::post('esqueci-a-senha/{token}', [ForgotPasswordController::class, 'setPassword']);