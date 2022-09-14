<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{lead, Status};
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Services\Messages;

class LeadStatusChange extends Action
{
	public $run_btn = "Alterar";
	public $title = "Alteração de status";
	public $message = "Essa ação irá alterar o status de todos os leads selecionados para o status selecionado";

	public function inputs()
	{
		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Status",
			"description" => "Novo status que deseja definir aos leads selecionados",
			"field" => "status_id",
			"rules" => ["required"],
			"model" => Status::class
		]);

		$cards = [];
		$cards[] = new Card("Informações", $fields);
		return $cards;
	}

	public function handler(Request $request)
	{
		$status = Status::findOrFail($request["status_id"]);
		Lead::whereIn("id", $request["ids"])->update(["status_id" => $status->id]);
		Messages::send("success", "Status dos Leads selecionados alterados para " . $status->name);
		return ['success' => true];
	}
}
