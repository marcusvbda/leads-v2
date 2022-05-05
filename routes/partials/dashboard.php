<?php

use App\Http\Controllers\DashboardController;

Route::group(['prefix' => "dashboard"], function () {
	Route::get('', [DashboardController::class, 'index']);
	Route::post('get-data/{action}', [DashboardController::class, 'getData']);
});
