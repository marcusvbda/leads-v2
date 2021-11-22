<?php

use App\Http\Controllers\WebhookController;


Route::post('webhooks/{token}', [WebhookController::class, "handler"]);
Route::post('webhook-events/handler', [WebhookController::class, "eventsHandler"]);
