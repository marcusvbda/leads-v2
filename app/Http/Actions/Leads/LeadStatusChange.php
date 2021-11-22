<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use marcusvbda\vstack\Services\Messages;

class LeadStatusChange extends Action
{
	public $id = "lead-status-change";
	public $run_btn = "Alterar";
	public $title = "Alteração de status";
	public $message = "Essa ação irá alterar o status de todos os leads selecionados para o status selecionado";


	public function inputs()
	{
		return [
			[
				"title" => 'Status',
				"id" => "status_id",
				"type" => "select",
				"required" => true,
				"options" =>  Status::selectRaw("id as value, name as label")->get()
			],
		];
	}

	public function handler(Request $request)
	{
		$status = Status::findOrFail($request["status_id"]);
		Lead::whereIn("id", $request["ids"])->update(["status_id" => $status->id]);
		Messages::send("success", "Status dos Leads selecionados alterados para " . $status->name);
		return ['success' => true];
	}
}
