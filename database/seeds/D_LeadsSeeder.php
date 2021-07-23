<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
	Status,
	Polo,
	Webhook,
	Lead
};
use Carbon\Carbon;

class D_LeadsSeeder extends Seeder
{
	private $webhook = null;
	private $polos = [];


	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->polos = Polo::pluck("id", "name")->toArray();
		$this->createWebhook();
		$this->createleads();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function createWebhook()
	{
		DB::table("webhooks")->truncate();
		$api_user = DB::connection("old_root_mysql")->table("_api_users")->where("client_id", "cb48038ef78ec109d520a673e1ee486b")->first();
		$this->webhook = Webhook::create([
			"name" => $api_user->nome,
			"token" => $api_user->client_id,
			"tenant_id" => 1,
		]);
	}

	private function createleads()
	{
		DB::table("leads")->truncate();
		DB::table("webhook_requests")->truncate();
		$old_leads = DB::connection("old_mysql")->table("_leads")
			->join("_fila_contato", "_fila_contato.lead_id", "=", "_leads.id")
			->join("_tenants", "_fila_contato.tenant_id", "=", "_tenants.id")
			->select(
				"_fila_contato.ref_token",
				"_fila_contato.log as log",
				"_fila_contato.outra_objecao",
				"_leads.observacoes as lead_obs",
				"_fila_contato.observacoes as fila_obs",
				"_leads.*",
				"_fila_contato.status_id",
				"_tenants.nome as tenant_name",
				"_fila_contato.objecao_id"
			)->get();
		foreach ($old_leads as $old_lead) {
			$old_lead_data = json_decode($old_lead->data);
			if (@$old_lead->id) {
				$request = null;
				if (@$old_lead_data->lead_api) {
					$request = $this->webhook->requests()->create([
						"content" => $old_lead_data->lead_api,
						"approved" => true
					]);
				}
				$old_status = DB::connection("old_mysql")->table("_status")->where("id", @$old_lead->status_id)->first();
				$objecao_id = @$old_lead->objecao_id ?  @$this->getObjection($old_lead->objecao_id) : null;
				$status = $this->getCurrentStatus($old_status, $objecao_id);
				$schedule = null;
				if ($old_status->value == "A") {
					$schedule = @$old_lead->data && @$old_lead->hora ? $old_lead->data . " - " . $old_lead->hora : Carbon::now()->format("Y-m-d H:i:s");
				}
				Lead::create([
					"polo_id" => $this->polos[$old_lead->tenant_name],
					"tenant_id" => 1,
					"data" => [
						"lead_api" => @$old_lead_data->lead_api ? $old_lead_data->lead_api : (object)[],
						"name" => @$old_lead->nome,
						"email" => @$old_lead->email,
						"phones" => $this->getPhones($old_lead),
						"schedule" => $schedule,
						"city" => @$old_lead->cidade,
						"interest" => @$old_lead->curso,
						"api_ref_token" => @$old_lead->ref_token,
						"obs" => @$old_lead->lead_obs == 'via RD Station' ? 'via RD Station ( ref_token :' . $old_lead->ref_token . ' )' : @$old_lead->lead_obs,
						"comment" => @$old_lead->fila_obs,
						"lead_api" => @$old_lead_data->lead_api,
						"objection" => $objecao_id,
						"other_objection" => @$old_lead_data->outra_objecao,
						"log" => $this->getLogs(@$old_lead->log ? json_decode($old_lead->log) : []),
						"tries" => $this->getTries(@$old_lead_data->tentativa ? $old_lead_data->tentativa : []),
					],
					"webhook_id" => $request ? $this->webhook->id : null,
					"webhook_request_id" => @$request->id,
					"user_id" => @!$request ? 1 : null,
					"status_id" => $status,
					"created_at" => @$old_lead->created_at,
					"updated_at" => @$old_lead->updated_at,
				]);
			}
		}
	}

	private function getLogs($logs)
	{
		$_log = [];
		foreach ($logs as $log) {
			$_log[] = [
				"date" => @$log->data,
				"timestamp" => @$log->hora,
				"obs" => @$log->observacoes,
				"user" => @$log->responsavel,
				"desc" => @$log->texto,
			];
		}
		return $_log;
	}

	private function getTries($tries)
	{
		$_tries = [];
		foreach ($tries as $_try) {
			$_tries[] = [
				"type" => @$_try->tipo->nome,
				"date" => @$_try->data,
				"timestamp" => @$_try->hora,
				"objection" => @$_try->resposta->objecao_id ? @$this->getObjection($_try->resposta->objecao_id) : null,
				"other_objection" => @$_try->resposta->outra_objecao,
				"obs" => @$_try->resposta->observacoes,
				"comment" => @$_try->resposta->comment,
			];
		}
		return $_tries;
	}

	private function getObjection($objecao_id)
	{
		return DB::connection("old_mysql")->table("_status")->where("id", $objecao_id)->first();
	}

	private function getPhones($row)
	{
		$result = [];
		$result[] = @$row->telefone ?? "";
		$result[] = @$row->telefone_2 ?? "";
		return $result;
	}

	private function getCurrentStatus($old_status, $objecao_id)
	{
		if ($objecao_id) {
			return Status::value("objection")->id;
		}
		switch ($old_status->value) {
			case "C":
				return Status::value("canceled")->id;
				break;
			case "A":
				return Status::value("schedule")->id;
				break;
			case "I":
				return Status::value("waiting")->id;
				break;
			case "V":
				return Status::value("test_done")->id;
				break;
			case "F":
				return Status::value("finished")->id;
				break;
			default:
				dd($old_status);
				break;
		}
	}
}