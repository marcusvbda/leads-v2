<?php

use App\Http\Controllers\LeadController;

Route::group(["prefix" => "leads"], function () {
	Route::post('{code}/convertion', [LeadController::class, 'finishConvert']);
});