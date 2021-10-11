<?php

use App\Http\Controllers\WebhookController;

Route::group(["prefix" => "webhooks"], function () {
    Route::post('{token}/store-settings', [WebhookController::class, 'storeSettings']);
});