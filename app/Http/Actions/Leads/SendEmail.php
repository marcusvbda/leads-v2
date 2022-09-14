<?php

namespace App\Http\Actions\Leads;

use App\Http\Controllers\AttendanceController;
use App\Http\Models\EmailTemplate;
use App\Http\Models\Lead;
use App\Http\Models\MailIntegrator;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Radio;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Fields\TextArea;
use marcusvbda\vstack\Services\Messages;

class SendEmail extends Action
{
	public $run_btn = "Enviar modelo de email";
	public $title = "Enviar mensagem via Email";
	public $message = "Preencha o formulário corretamente enviar Email para os leads selecionados";

	public function inputs()
	{
		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Integrador",
			"description" => "Integrador responsável pelo envio do email",
			"field" => "integrator_id",
			"rules" => ["required"],
			"model" => MailIntegrator::class
		]);
		$fields[] = new Radio([
			"label" => "Tipo de Mensagem",
			"field" => "type",
			"rules" => ["required"],
			"options" => [
				["label" => "Modelo Pré-Definido", "value" => "template"],
				["label" => "Customizada", "value" => "custom"],
			],
			"default" => "template"
		]);


		$cards = [];
		$cards[] = new Card("Configurações", $fields);

		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Modelo de Email",
			"description" => "Modelo pré-definido de Email que deseja enviar",
			"field" => "template_id",
			"rules" => ["nullable", "required_if:type,template"],
			"eval" => 'v-if="form.type == `template`"',
			"model" => EmailTemplate::class
		]);
		$fields[] = new Text([
			"label" => "Assunto",
			"field" => "subject",
			"eval" => 'v-if="form.type == `custom`"',
			"rules" => ["nullable", "required_if:type,custom"]
		]);
		$fields[] = new TextArea([
			"label" => "Corpo do Email",
			"rows" => 10,
			"description" => "Digite a mensagem",
			"field" => "body",
			"eval" => 'v-if="form.type == `custom`"',
			"rules" => ["nullable", "required_if:type,custom"]
		]);
		$cards[] = new Card("Email", $fields);

		return $cards;
	}

	public function handler(Request $request)
	{
		(new AttendanceController)->dispatchEmailTemplate(new Request([
			"ids" => $request->ids,
			"sending_email" => $request->all()
		]));
		return ['success' => true];
	}
}
