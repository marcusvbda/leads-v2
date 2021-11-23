<?php

use App\Http\Controllers\ApiController;

Route::group(['middleware' => ['api.basic_auth']], function () {
    Route::group(['prefix' => "v1"], function () {
        Route::post('test-auth', [ApiController::class, "testAuth"]);
        Route::get('get-events', [ApiController::class, "getEvents"]);
        Route::post('event-handler', [ApiController::class, "eventHandler"]);
    });
});
