<?php

namespace App\Http\Controllers;

use App\Http\Models\Lead;
use App\Http\Models\Status;
use App\Http\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use marcusvbda\vstack\Services\Messages;

class WebhookController extends Controller
{
	public function handler($token, Request $request)
	{
		$webhook = Webhook::where('token', $token)->firstOrFail();
		$webhook->requests()->create(['content' => $request->all()]);
		$this->processNotApprovedRequests($webhook);
		return response('OK');
	}

	public function storeSettings($token, Request $request)
	{
		$webhook = Webhook::where('token', $token)->firstOrFail();
		$data = $request->all();
		$setting = $webhook->settings()->firstOrNew([
			'index' => $data['index'],
		]);
		$setting->fill($data);
		$setting->save();
		$this->processNotApprovedRequests($webhook);
		Messages::send("success", "Configuração de webhook salva com sucesso !!");
		return ["success" => true];
	}

	private function processNotApprovedRequests($webhook)
	{
		$settings = $webhook->settings;
		$requests = $webhook->requests()->where("approved", "=", false)->get();
		foreach ($requests as $request) {
			$this->processRequest($webhook, $settings, $request);
		}
	}

	private function processRequest($webhook, $settings, $request)
	{
		foreach ($settings as $setting) {
			if (Arr::get($request->content, $setting->index) === $setting->value) {
				$this->createLead($request, $webhook, $setting);
				$request->approved = true;
				$request->save();
				break;
			}
		}
	}

	private function createLead($request, $webhook, $setting)
	{
		$name = Arr::get($request->content, "name");
		$email = Arr::get($request->content, "email");
		$lead = new Lead;
		$lead->polo_id = $setting->polo_id;
		$lead->tenant_id = $webhook->tenant_id;
		$lead->webhook_id = $webhook->id;
		$lead->webhook_request_id = $request->id;
		$lead->status_id = Status::value("waiting")->id;
		$lead->data = [
			"lead_api" => $request->content,
			"city" => Arr::get($request->content, "city") . " " . Arr::get($request->content, "state"),
			"email" => $email,
			"name" => $name,
			"phones" => [Arr::get($request->content, "personal_phone"), Arr::get($request->content, "mobile_phone")],
			"city" => @$request->content->lastcidade,
			"obs" => 'via Webhook ( ' . $webhook->name . ' )',
			"comment" => ""
		];
		$lead->save();
	}
}