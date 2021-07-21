<?php

use App\Http\Controllers\AttendanceController;

Route::group(['prefix' => "atendimento"], function () {
	Route::get('', [AttendanceController::class, 'index']);
});