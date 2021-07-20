<?php

use App\Http\Controllers\RatingController;

Route::group(["prefix" => "regra-classificacao"], function () {
	Route::get('', [RatingController::class, 'index']);
	Route::post('', [RatingController::class, 'store']);
});