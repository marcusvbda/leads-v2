<?php

use App\Http\Controllers\CampaignController;

Route::group(["prefix" => "campanhas"], function () {
	Route::get('{code}/dashboard', [CampaignController::class, 'dashboard']);
});
