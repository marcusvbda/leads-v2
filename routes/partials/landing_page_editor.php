<?php

use App\Http\Controllers\LandingPageController;

Route::group(["prefix" => "admin/landing-pages"], function () {
    Route::get('editor/{code}', [LandingPageController::class, 'editor']);
});
