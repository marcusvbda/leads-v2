<?php

use App\Http\Controllers\LogViewerController;


Route::group(["prefix" => "log-viewer"], function () {
    Route::get('', [LogViewerController::class, 'index']);
    Route::post('get-content', [LogViewerController::class, 'getContent']);
});
