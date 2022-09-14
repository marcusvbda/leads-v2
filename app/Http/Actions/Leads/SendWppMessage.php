<?php

namespace App\Http\Actions\Leads;

use App\Http\Models\Lead;
use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\TextArea;
use marcusvbda\vstack\Services\Messages;

class SendWppMessage extends Action
{
	public $run_btn = "Adicionar a fila de disparos";
	public $title = "Enviar mensagem via WhatsApp";
	public $message = "Preencha o formulário corretamente para criar uma mensagem a mensagem";

	public function inputs()
	{
		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Sessão",
			"description" => "Sessão do whatsApp que deseja utilizar",
			"field" => "session_id",
			"rules" => ["required"],
			"model" => WppSession::class
		]);

		$cards = [];
		$cards[] = new Card("Configurações", $fields);

		$fields = [];
		$fields[] = new TextArea([
			"label" => "Mensagem",
			"type" => "textarea",
			"rows" => 10,
			"description" => "Digite a mensagem",
			"field" => "mensagem",
			"rules" => ["required"]
		]);
		$cards[] = new Card("Email", $fields);

		return $cards;
	}

	public function handler(Request $request)
	{
		$user = Auth::user();
		$ids = $request->ids;
		$created_ids = [];
		$data = (array)$request->all();
		foreach ($ids as $id) {
			$lead = Lead::find($id);
			$phone = $lead->primary_phone_number;
			if ($phone) {
				$_data = $data;
				$_data["telefone"] = "+55" . $phone;
				unset($_data["ids"]);
				$created = WppMessage::create([
					"wpp_session_id" => $request->session_id,
					"polo_id" => $user->polo_id,
					"user_id" => $user->id,
					"data" => $_data
				]);
				$created_ids[] = $created->id;
			}
		}
		Messages::send("success", "Mensagens adicionadas a fila de disparo !");
		return ['success' => true];
	}
}
