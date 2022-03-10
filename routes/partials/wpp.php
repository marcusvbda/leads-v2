<?php

use App\Http\Controllers\WppController;

Route::group(["prefix" => "wpp"], function () {
    // Route::get('sender', [WppController::class, "sender"]);
    Route::post('token-update', [WppController::class, "tokenUpdate"]);
});
