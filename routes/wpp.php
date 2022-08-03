<?php

use App\Http\Controllers\{WppMessagesController, WppSessionController};

Route::group(["prefix" => "mensagens-wpp"], function () {
    Route::post('postback/{tenant_code}', [WppMessagesController::class, "postback"]);
});


Route::group(['prefix' => "sessoes-wpp"], function () {
    Route::post('/postback/{code}', [WppSessionController::class, 'postback']);
});
