<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{Polo, lead};
use Auth;
use marcusvbda\vstack\Services\Messages;

class LeadTransfer extends Action
{
	public $id = "lead-transfer";
	public $run_btn = "Executar";
	public $title = "Transferência de Leads";
	public $polo = null;
	public function __construct()
	{
		$user = Auth::user();
		$this->polo = @$user->polo;
		if (@$this->polo) {
			$this->message = "Estes leads pertencem ao polo {$this->polo->name}, selecione o polo para qual deseja transferir os leads selecionados";
		}
	}

	public function inputs()
	{
		return [
			[
				"title" => 'Polo',
				"id" => "polo_id",
				"type" => "select",
				"required" => true,
				"options" =>  Polo::selectRaw("id as value, name as label")->where("id", "!=", $this->polo->id)->get()
			],
		];
	}

	public function handler(Request $request)
	{
		$polo = Polo::findOrFail($request["polo_id"]);
		Lead::whereIn("id", $request["ids"])->update(["polo_id" => $polo->id]);
		Messages::send("success", "Leads selecionados transferidos para " . $polo->name);
		return ['success' => true];
	}
}