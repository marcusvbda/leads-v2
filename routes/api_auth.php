<?php

use App\Http\Controllers\ApiController;

Route::group(['prefix' => "v1"], function () {
    Route::group(['prefix' => "auth"], function () {
        Route::post('generate-token', [ApiController::class, "generateToken"]);
    });
});
