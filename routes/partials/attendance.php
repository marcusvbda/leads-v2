<?php

use App\Http\Controllers\AttendanceController;

Route::group(['prefix' => "atendimento"], function () {
	Route::get('', [AttendanceController::class, 'index']);
	Route::post('{code}/register-contact', [AttendanceController::class, 'registerContact']);
	Route::post('{code}/transfer-department', [AttendanceController::class, 'transferDepartment']);
	Route::post('{code}/finish', [AttendanceController::class, 'finish']);
});
