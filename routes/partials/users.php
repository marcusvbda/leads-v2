<?php

use App\Http\Controllers\Auth\UsersController;

Route::group(["prefix" => "usuarios"], function () {
	Route::get('cancel_invite/{code}', [UsersController::class, "cancelInvite"])->middleware(['hashids:code']);
});

Route::group(["prefix" => "profile"], function () {
	Route::get('', [UsersController::class, "profile"]);
	Route::post('', [UsersController::class, "profileEdit"]);
});
