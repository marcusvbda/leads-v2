<?php

namespace App\Http\Controllers;

use App\Http\Models\Lead;
use App\Http\Models\Status;
use App\Http\Models\UserNotification;
use App\Http\Models\Webhook;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use marcusvbda\vstack\Services\Messages;

class WebhookController extends Controller
{
	public function handler($token, Request $request)
	{
		$webhook = Webhook::where('token', $token)->firstOrFail();
		$createdRequest = $webhook->requests()->create(['content' => $request->all()]);
		$processed = $this->processRequest($webhook, $webhook->settings, $createdRequest);
		if (!$processed) {
			$this->sendNotProcessedRequestNotificationToAdminUsers($webhook);
		}
		return response('OK');
	}

	public function actions($code, $action, Request $request)
	{
		$webhook = Webhook::findByCode($code);
		$methodName = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action))));
		return $this->{$methodName}($webhook, $request);
		return $actions[$action]($data);
	}

	protected function destroySettings($webhook, Request $request)
	{
		$webhook->settings()->findOrFail($request["id"])->delete();
		Messages::send("success", "Configuração removida com sucesso !!");
		return ["success" => true];
	}

	protected function destroyRequests($webhook, Request $request)
	{
		$webhook->requests()->findOrFail($request["id"])->delete();
		Messages::send("success", "Request removido com sucesso !!");
		return ["success" => true];
	}

	private function getSortedObject($obj)
	{
		$newObj = [];
		$value = (array) $obj;
		$keys = array_keys($value);
		sort($keys);
		foreach ($keys as $key) {
			$newObj[$key] = $value[$key];
		}
		return $newObj;
	}

	private function getIndexesObject($values)
	{
		$indexes = [];
		foreach ($values as $key => $value) {
			$indexes[] = $key . "=>" . $value;
		}
		return (implode('|', $indexes));
	}

	public function storeSettings($token, Request $request)
	{
		$webhook = Webhook::where('token', $token)->firstOrFail();
		$data = $request->all();
		$indexes = $this->getIndexesObject($this->getSortedObject($data["value"]));
		$setting = $webhook->settings()->firstOrNew([
			'indexes' => $indexes,
		]);
		$setting->polo_id = $data["polo_id"];
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
			$indexes = $setting->indexes;
			$content = $request->content;
			$indexes = explode("|", $indexes);
			$hasPositiveResults = count(array_filter(array_map(function ($row) use ($content) {
				$explodedRow = explode("=>", $row);
				$index = $explodedRow[0];
				$value = $explodedRow[1];
				return $value === Arr::get($content, $index);
			}, $indexes))) == count($indexes);
			if ($hasPositiveResults) {
				$this->createLead($request, $webhook, $setting);
				$request->approved = true;
				$request->save();
				return true;
			}
		}
		return false;
	}

	private function createLead($request, $webhook, $setting)
	{
		$status = Status::value("waiting");
		$name = Arr::get($request->content, "name");
		$email = Arr::get($request->content, "email");
		$lead = Lead::where('data->name', $name)->where('data->email', $email)->where("status_id", $status->id)->first() ?? new Lead;
		$lead->polo_id = $setting->polo_id;
		$lead->tenant_id = $webhook->tenant_id;
		$lead->webhook_id = $webhook->id;
		$lead->webhook_request_id = $request->id;
		if (@$lead->id) {
			$lead->status_id = Status::value("waiting")->id;
		}
		$comment = @$lead->comment ?? '';
		$obs = @$lead->obs ?? 'via Webhook ( ' . $webhook->name . ' )';
		$lead->data = [
			"lead_api" => $request->content,
			"city" => Arr::get($request->content, "city") . " " . Arr::get($request->content, "state"),
			"email" => $email,
			"name" => $name,
			"phones" => [Arr::get($request->content, "personal_phone"), Arr::get($request->content, "mobile_phone")],
			"city" => @$request->content->lastcidade,
			"obs" => $obs,
			"comment" => $comment
		];
		$lead->save();
	}

	private function sendNotProcessedRequestNotificationToAdminUsers($webhook)
	{
		$polos = $webhook->tenant->polos()->where('data->head', true)->get();
		foreach ($polos as $polo) {
			UserNotification::create([
				"polo_id" => $polo->id,
				"data" => [
					"message" => "Novo request sem configuração de direcionamento de lead no webhook <b>" . $webhook->name . "</b>",
					"icon" => "el-icon-finished",
					"url" => "/admin/webhooks/" . $webhook->code
				]
			]);
		}
	}
}