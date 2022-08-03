<?php

use App\Http\Controllers\WppSessionController;

Route::group(['prefix' => "sessoes-wpp"], function () {
	Route::post('/login', [WppSessionController::class, 'createSession']);
});
