<?php

use App\Http\Controllers\DatesController;

Route::group(["prefix" => "dates"], function () {
	Route::post('get-ranges', [DatesController::class, 'getRanges']);
});