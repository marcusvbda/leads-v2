<?php

namespace App\Http\Actions\Leads;

use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
// use App\Http\Models\{Polo, lead};
// use Auth;
// use marcusvbda\vstack\Services\Messages;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SendWppMessage extends Action
{
	use ValidatesRequests;

	public $id = "wpp-message";
	public $run_btn = "Enviar";
	public $title = "Envio de mensagem via WhatsApp";
	public $message = "Preencha o formulário corretamente para enviar a mensagem";


	public function inputs()
	{
		return [
			[
				"type" => "custom",
				"template" => "<h1>Teste 123</h1>"
			],
		];
	}

	public function handler(Request $request)
	{
		$this->validate($request, [
			'telefone' => 'required'
		]);
		dd("tste");
		// $status = Status::findOrFail($request["status_id"]);
		// Lead::whereIn("id", $request["ids"])->update(["status_id" => $status->id]);
		// Messages::send("success", "Status dos Leads selecionados alterados para " . $status->name);
		return ['success' => true];
	}
}
