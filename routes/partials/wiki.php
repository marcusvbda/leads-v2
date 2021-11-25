<?php

use App\Http\Controllers\WikiController;

Route::group(["prefix" => "wiki-page"], function () {
    Route::get('{path}', [WikiController::class, "show"]);
});
