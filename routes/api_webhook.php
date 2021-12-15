<?php

use App\Http\Controllers\WebhookController;


Route::group(['prefix' => "webhooks"], function () {
    Route::get('{token}/script.js', [WebhookController::class, "scriptDirectRequest"]);
    Route::post('{token}', [WebhookController::class, "handler"]);
});
