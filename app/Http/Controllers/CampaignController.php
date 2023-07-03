<?php

namespace App\Http\Controllers;

use App\Http\Resources\Campanhas;
use App\Http\Resources\Leads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
	public function __construct(Campanhas $resource)
	{
		$this->resource = $resource;
		$this->model = $this->resource->getModelInstance();
		$this->leads_fields = Leads::getFieldListOption();
	}

	public function dashboard($code)
	{
		$campaign = $this->model->findByCode($code);
		$resource = $this->resource;
		$leads_fields = $this->leads_fields;
		return view("admin.campaign.page_campaign_dashboard", compact("campaign", "resource", "leads_fields"));
	}

	public function qtyLeads($code)
	{
		$campaign = $this->model->findByCode($code);
		$qty = $campaign->leads()->count();
		return response()->json(["qty" => $qty]);
	}

	public function fetchLeads($code, Request $request)
	{
		$campaign = $this->model->findByCode($code);
		$leads = $campaign->leads()->where("stage_position", $request->stage)->cursorPaginate(10);
		return response()->json($leads);
	}

	public function SaveLead($code, Request $request)
	{
		$campaign = $this->model->findByCode($code);
		$now = date("Y-m-d H:i:s");
		DB::table("campaign_leads")->where("campaign_id", $campaign->id)->where("lead_id", $request->lead_id)->update(["updated_at" => $now, "stage_position" => $request->new_stage]);
		return response()->json(["success" => true]);
	}
}
