<?php

use App\Http\Controllers\PoloController;

Route::group(["prefix" => "polos"], function () {
	Route::post('change-logged', [PoloController::class, 'changeLogged']);
});