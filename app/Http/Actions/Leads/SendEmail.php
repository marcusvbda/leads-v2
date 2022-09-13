<?php

namespace App\Http\Actions\Leads;

use App\Http\Models\EmailTemplate;
use App\Http\Models\Lead;
use App\Http\Models\MailIntegrator;
use  marcusvbda\vstack\Action;
use Illuminate\Http\Request;
use marcusvbda\vstack\Services\Messages;

class SendEmail extends Action
{
	public $run_btn = "Enviar modelo de email";
	public $title = "Enviar mensagem via Email";
	public $message = "Preencha o formulário corretamente enviar Email para os leads selecionados";

	public function inputs()
	{
		$options = [];
		foreach (MailIntegrator::get() as $item) {
			$options[] = ["value" => $item->id, "label" => $item->name];
		}

		$option_templates = [];
		foreach (EmailTemplate::get() as $item) {
			$option_templates[] = ["value" => $item->id, "label" => $item->name];
		}

		return [
			[
				"title" => 'Integrador de Email',
				"id" => "integrator_id",
				"type" => "select",
				"required" => true,
				"options" =>  $options
			],
			[
				"title" => 'Modelo de Email',
				"id" => "template_id",
				"type" => "select",
				"required" => true,
				"options" =>  $option_templates
			],
		];
	}

	public function handler(Request $request)
	{
		$ids = $request->ids;
		$integrator_id = $request->integrator_id;
		$template_id = $request->template_id;
		dispatch(function () use ($ids, $integrator_id, $template_id) {
			$leads = Lead::whereIn("id", $ids)->whereNotNull("data->email")->get();
			$integrator = MailIntegrator::findOrFail($integrator_id);
			$template = EmailTemplate::findOrFail($template_id);

			foreach ($leads as $lead) {
				$template->send([
					"address" => $lead->email,
					"integrator" => $integrator,
					"template_process" => true,
					"process_context" => $lead->toArray()
				]);
			}
		})->onQueue("mail-integrator");
		Messages::send("success", "Mensagens enviadas com sucesso !");
		return ['success' => true];
	}
}
