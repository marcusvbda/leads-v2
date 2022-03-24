<?php

use App\Http\Controllers\WppMessagesController;

Route::group(["prefix" => "mensagens-wpp"], function () {
    Route::post('postback', [WppMessagesController::class, "postback"]);
});
