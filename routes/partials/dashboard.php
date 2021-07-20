<?php

use App\Http\Controllers\DashboardController;

Route::get('', [DashboardController::class, 'index']);
Route::group(['prefix' => "dashboard"], function () {
	Route::post('get-data/{action}', [DashboardController::class, 'getData']);
});