<?php

use App\Http\Controllers\CampaignController;

Route::group(["prefix" => "campanhas"], function () {
	Route::get('{code}/dashboard', [CampaignController::class, 'dashboard']);
	Route::get('{code}/qty-leads', [CampaignController::class, 'qtyLeads']);
	Route::post('{code}/fetch-leads', [CampaignController::class, 'fetchLeads']);
	Route::post('{code}/save-lead', [CampaignController::class, 'SaveLead']);
});
