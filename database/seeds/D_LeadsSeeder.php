<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
	ApiUser,
	Polo
};

class D_LeadsSeeder extends Seeder
{
	private $user_api = null;
	private $polos = [];


	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->polos = Polo::pluck("id", "name")->toArray();
		$this->createUserApi();
		$this->createleads();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function createUserApi()
	{
		DB::table("api_users")->truncate();
		$api_user = DB::connection("old_root_mysql")->table("_api_users")->where("client_id", "cb48038ef78ec109d520a673e1ee486b")->first();
		$this->user_api = ApiUser::create([
			"name" => $api_user->nome,
			"token" => $api_user->client_id,
			"secret_key" => $api_user->secret_key,
			"tenant_id" => 1,
		]);
	}

	private function createleads()
	{
		DB::table("leads")->truncate();
		// $old_leads = DB::connection("old_mysql")->table("_leads")
		// 	->join("_fila_contato", "_fila_contato.lead_id", "=", "_leads.id")
		// 	->join("_tenants", "_fila_contato.tenant_id", "=", "_tenants.id")
		// 	->select(
		// 		"_fila_contato.ref_token",
		// 		"_fila_contato.log as log",
		// 		"_fila_contato.outra_objecao",
		// 		"_leads.observacoes as lead_obs",
		// 		"_fila_contato.observacoes as fila_obs",
		// 		"_leads.*",
		// 		"_fila_contato.status_id",
		// 		"_tenants.nome as tenant_name",
		// 		"_fila_contato.objecao_id"
		// 	)->get();
		// foreach ($old_leads as $old_lead) {
		// 	$old_lead_data = json_decode($old_lead->data);
		// 	if (@$old_lead->id) {
		// 		$status = $this->getCurrentStatus($old_lead);
		// 		Lead::create([
		// 			"polo_id" => $this->polos[$old_lead->tenant_name],
		// 			"tenant_id" => 1,
		// 			"data" => [
		// 				"lead_api" => @$old_lead_data->lead_api ? $old_lead_data->lead_api : (object)[],
		// 				"name" => @$old_lead->nome,
		// 				"email" => @$old_lead->email,
		// 				"phones" => $this->getPhones($old_lead),
		// 				"city" => @$old_lead->cidade,
		// 				"interest" => @$old_lead->curso,
		// 				"api_ref_token" => @$old_lead->ref_token,
		// 				"obs" => @$old_lead->lead_obs == 'via RD Station' ? 'via RD Station ( ref_token :' . $old_lead->ref_token . ' )' : @$old_lead->lead_obs,
		// 				"comment" => @$old_lead->fila_obs,
		// 				"lead_api" => @$old_lead_data->lead_api,
		// 				"objection" => @$this->getObjection($status, $old_lead->objecao_id),
		// 				"other_objection" => @$old_lead_data->outra_objecao,
		// 				"log" => $this->getLogs(@$old_lead->log ? json_decode($old_lead->log) : []),
		// 				"tries" => $this->getTries(@$old_lead_data->tentativa ? $old_lead_data->tentativa : []),
		// 			],
		// 			"api_user_id" => @$old_lead->observacoes == 'via RD Station' ? $this->user_api->id : null,
		// 			"user_id" => @$old_lead->observacoes != 'via RD Station' ? 1 : null,
		// 			"lead_substatus_id" => $status,
		// 			"created_at" => @$old_lead->created_at,
		// 			"updated_at" => @$old_lead->updated_at,
		// 		]);
		// 	}
		// }
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
				"objection" => @$this->getObjection("Não Qualificado", $_try->resposta->objecao_id),
				"other_objection" => @$_try->resposta->outra_objecao,
				"obs" => @$_try->resposta->observacoes,
				"comment" => @$_try->resposta->comment,
			];
		}
		return $_tries;
	}

	private function getObjection($status, $objecao_id)
	{
		if ($status == "Não Qualificado") {
			$status = DB::connection("old_mysql")->table("_status")->where("id", $objecao_id)->first();
			return $status->name;
		}
		return null;
	}

	private function getPhones($row)
	{
		$result = [];
		$result[] = @$row->telefone ?? "";
		$result[] = @$row->telefone_2 ?? "";
		return $result;
	}

	private function getCurrentStatus($row)
	{
		$status = DB::connection("old_mysql")->table("_status")->where("id", @$row->status_id)->first();
		switch ($status->value) {
			case "C":
				return LeadSubstatus::where("value", "canceled")->firstOrFail()->id;
				break;
			case "A":
				return LeadSubstatus::where("value", "schedule")->firstOrFail()->id;
				break;
			case "I":
				return LeadSubstatus::where("value", "new_contact")->firstOrFail()->id;
				break;
			case "V":
				return LeadSubstatus::where("value", "test")->firstOrFail()->id;
				break;
			case "F":
				return LeadSubstatus::where("value", "test_done")->firstOrFail()->id;
				break;
			default:
				dd($status);
				break;
		}
	}
}