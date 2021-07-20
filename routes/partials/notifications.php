<?php

use App\Http\Controllers\NotificationsController;

Route::group(["prefix" => "notificacoes"], function () {
	Route::get('', [NotificationsController::class, "index"]);
	Route::post('get-qty', [NotificationsController::class, "getQty"]);
	Route::post('paginated', [NotificationsController::class, "paginated"]);
});