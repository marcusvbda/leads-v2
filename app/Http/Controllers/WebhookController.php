<?php

namespace App\Http\Controllers;

use App\Http\Models\Lead;
use App\Http\Models\Status;
use App\Http\Models\UserNotification;
use App\Http\Models\Webhook;
use App\Http\Models\WebhookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use marcusvbda\vstack\Services\Messages;

class WebhookController extends Controller
{

	const INDEXES = [
		"name" => ["name", "nome", "first_name"],
		"email" => ["email"],
		"city" => ["city", "cidade", "Cidade Aberto"],
		"state" => ["state", "estado", "Estado Aberto"],
		"phone" => ["personal_phone", "Telefone Pessoal", "Telefone"],
		"mobile_phone" => ["mobile_phone", "Telefone Movel", "Celular"],
	];

	public function handler($token, Request $request, $lead_id = null)
	{
		$webhook = Webhook::where('token', $token)->firstOrFail();
		$createdRequest = $webhook->requests()->create(['content' => $request->all()]);
		$processed = $this->processRequest($webhook, $webhook->settings, $createdRequest, $lead_id);
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
	}

	public function setHideValue($webhook, Request $request)
	{
		$request = WebhookRequest::findOrFail($request->row_id);
		$request->hide = $request->hide == 'hide' ? false : true;
		$request->save();
		return ["success" => true];
	}

	protected function destroySettings($webhook, Request $request)
	{
		$setting = $webhook->settings()->findOrFail($request["id"]);
		$setting->delete();
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

	private function processRequest($webhook, $settings, $request, $lead_id = null)
	{
		foreach ($settings as $setting) {
			$indexes = $setting->indexes;
			$content = $request->content; //content está sendo usado em eval, não remover
			$indexes = explode("|", $indexes);
			$hasPositiveResults = count(array_filter(array_map(function ($row) use ($content) { //content está sendo usado em eval, não remover
				$explodedRow = explode("=>", $row);
				$index = $explodedRow[0];
				$value = $explodedRow[1];
				$complete_index = '@$content' . $index;
				eval('$content_value = ' . $complete_index . ' ? ' . $complete_index . ' : null;');
				return $value === $content_value;
			}, $indexes))) == count($indexes);
			if ($hasPositiveResults) {
				$this->createLead($request, $webhook, $setting, $lead_id);
				$request->approved = true;
				$request->hide = false;
				$request->save();
				return true;
			}
		}
		return false;
	}

	private function getLeadInfo($content, $indexes = [])
	{
		foreach ($indexes as $index) {
			$value = Arr::get((@$content ? $content : []), $index);
			if ($value) {
				return $value;
			}
			$value = Arr::get((@$content["lead_api"]["leads"][0] ? $content["lead_api"]["leads"][0] : []), $index);
			if ($value) {
				return $value;
			}
			$value = Arr::get((@$content["leads"][0] ? $content["leads"][0] : []), $index);
			if ($value) {
				return $value;
			}
			$value = Arr::get((@$content["lead_api"] ? $content["lead_api"] : []), $index);
			if ($value) {
				return $value;
			}
			$value = Arr::get((@$content["lead_api"]["last_conversion"]["content"] ? $content["lead_api"]["last_conversion"]["content"] : []), $index);
			if ($value) {
				return $value;
			}
			$value = Arr::get((@$content["lead_api"]["first_conversion"]["content"] ? $content["lead_api"]["first_conversion"]["content"] : []), $index);
			if ($value) {
				return $value;
			}
		}
		return null;
	}

	private function createLead($request, $webhook, $setting, $lead_id = null)
	{
		$name = $this->getLeadInfo($request->content, static::INDEXES["name"]);
		$email = $this->getLeadInfo($request->content, static::INDEXES["email"]);

		if (!$lead_id) {
			$lead = Lead::where('data->name', $name)->where('data->email', $email)->first() ?? new Lead;
		} else {
			$lead = Lead::findOrFail($lead_id);
		}

		$lead->polo_id = $setting->polo_id;
		$lead->tenant_id = $webhook->tenant_id;
		$lead->webhook_id = $webhook->id;
		$lead->webhook_request_id = $request->id;
		if (@$lead->id) {
			$lead->status_id = $status->id;
		}
		$comment = @$lead->comment ?? '';
		$obs = @$lead->obs ?? 'via Webhook ( ' . $webhook->name . ' )';
		$lead->data = [
			"lead_api" => $request->content,
			"city" => $this->getLeadInfo($request->content, static::INDEXES["city"]) . " " . $this->getLeadInfo($request->content, static::INDEXES["state"]),
			"email" => $email,
			"name" => $name,
			"phones" => [$this->getLeadInfo($request->content, static::INDEXES["phone"]), $this->getLeadInfo($request->content, static::INDEXES["mobile_phone"])],
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

	public function eventsHandler(Request $request)
	{
		$this->validate($request, [
			"integration_key" => "required|string",
			"event" => "required|string"
		]);
		return response('OK');
	}
}
