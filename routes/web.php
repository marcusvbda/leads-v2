<?php

use App\Http\Controllers\Auth\UsersController;

Route::get('', function () {
	return redirect("/admin"); //temporário até termos uma landing page
});

require "partials/auth.php";
Route::group(['middleware' => ['auth']], function () {
	Route::group(['prefix' => "admin"], function () {
		require "partials/campaign.php";
		require "partials/home.php";
		require "partials/dashboard.php";
		require "partials/users.php";
		require "partials/polos.php";
	});
});

Route::get('user_invite/{tenant_id}/{invite_md5}', [UsersController::class, 'userCreate'])->middleware(['hashids:tenant_id'])->name("user.create");
Route::post('user_invite/{tenant_id}/{invite_md5}', [UsersController::class, 'userConfirm'])->middleware(['hashids:tenant_id'])->name("user.confirm");
