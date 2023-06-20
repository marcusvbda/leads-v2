<?php

namespace App\Http\Controllers;

use App\Http\Models\Campaign;
use App\Http\Resources\Campanhas;

class CampaignController extends Controller
{
	public function dashboard($code)
	{
		$resource = new Campanhas();
		$campaign = Campaign::findByCode($code);
		return view("admin.campaign.page_campaign_dashboard", compact("campaign", "resource"));
	}
}
