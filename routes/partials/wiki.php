<?php

use App\Http\Controllers\WikiController;

Route::group(['prefix' => "wiki"], function () {
	Route::get('', [WikiController::class, 'index']);
});
