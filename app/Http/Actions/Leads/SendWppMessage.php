<?php

namespace App\Http\Actions\Leads;

use App\Http\Models\Lead;
use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Artisan;
use marcusvbda\vstack\Services\Messages;

class SendWppMessage extends Action
{
	public $id = "wpp-message";
	public $run_btn = "Adicionar a fila de disparos";
	public $title = "Enviar mensagem via WhatsApp";
	public $message = "Preencha o formulário corretamente para criar uma mensagem a mensagem";

	public function inputs()
	{
		$options = [];
		foreach (WppSession::get() as $item) {
			$options[] = ["value" => $item->id, "label" => $item->name];
		}

		return [
			[
				"title" => 'Sessão',
				"id" => "session_id",
				"type" => "select",
				"required" => true,
				"options" =>  $options
			],
			[
				"title" => 'Mensagem',
				"id" => "mensagem",
				"type" => "textarea",
				"required" => true,
				"rows" => 10
			],
		];
	}

	public function handler(Request $request)
	{
		$user = Auth::user();
		$ids = $request->ids;
		$created_ids = [];
		foreach ($ids as $id) {
			$lead = Lead::find($id);
			$phone = $lead->primary_phone_number;
			if ($phone) {
				$data = (array)$request->all();
				$data["telefone"] = "+55" . $phone;
				$new_message = new WppMessage();
				$new_message->wpp_session_id = $request->session_id;
				$new_message->polo_id = $user->polo_id;
				$new_message->user_id = $user->id;
				unset($data["ids"]);
				$new_message->data = $data;
				$new_message->save();
				$created_ids[] = $new_message->id;
			}
		}
		Artisan::queue("command:send-wpp-message", ["ids" => $created_ids, "session_id" => $request->session_id]);
		Messages::send("success", "Mensagens adicionadas a fila de disparo !");
		return ['success' => true];
	}
}
