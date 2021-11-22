<?php

use App\Http\Controllers\WebhookController;

Route::group(["prefix" => "webhooks"], function () {
    Route::post('{token}/store-settings', [WebhookController::class, 'storeSettings']);
    Route::post('{code}/actions/{action}', [WebhookController::class, 'actions']);
});
