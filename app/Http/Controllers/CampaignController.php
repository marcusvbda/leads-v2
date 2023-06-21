<?php

namespace App\Http\Controllers;

use App\Http\Resources\Campanhas;

class CampaignController extends Controller
{
	public function dashboard($code)
	{
		$resource = new Campanhas();
		$campaign = $resource->getModelInstance()->findByCode($code);
		return view("admin.campaign.page_campaign_dashboard", compact("campaign", "resource"));
	}

	public function qtyLeads($code)
	{
		$resource = new Campanhas();
		$campaign = $resource->getModelInstance()->findByCode($code);
		$qty = $campaign->leads()->count();
		return response()->json(["qty" => $qty]);
	}
}
