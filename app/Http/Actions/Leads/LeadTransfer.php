<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use App\Http\Models\{Polo, lead};
use Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Services\Messages;

class LeadTransfer extends Action
{
	public $run_btn = "Transferir";
	public $title = "Transferência entre polos";
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
		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Polo",
			"description" => "Polo o qual receberá este lead",
			"field" => "polo_id",
			"rules" => ["required"],
			"model" => Polo::class
		]);

		$cards = [];
		$cards[] = new Card("Informações", $fields);
		return $cards;
	}

	public function handler(Request $request)
	{
		$polo = Polo::findOrFail($request["polo_id"]);
		Lead::whereIn("id", $request["ids"])->update(["polo_id" => $polo->id]);
		Messages::send("success", "Leads selecionados transferidos para " . $polo->name);
		return ['success' => true];
	}
}
