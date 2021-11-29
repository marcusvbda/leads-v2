<?php

use App\Http\Controllers\ApiController;

Route::group(['middleware' => ['api.basic_auth']], function () {
    Route::group(['prefix' => "v1"], function () {
        Route::post('test-auth', [ApiController::class, "postTestAuth"]);
        Route::get('get-events', [ApiController::class, "getEvents"]);
        Route::get('get-actions', [ApiController::class, "getActions"]);
        Route::post('event-handler', [ApiController::class, "postEventHandler"]);
    });
});
