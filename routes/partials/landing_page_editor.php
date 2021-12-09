<?php

use App\Http\Controllers\LandingPageController;

Route::group(["prefix" => "landing-page-editor"], function () {
    Route::get('', [LandingPageController::class, 'index']);
});
