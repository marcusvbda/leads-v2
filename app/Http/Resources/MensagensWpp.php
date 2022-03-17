<?php

namespace App\Http\Resources;

use App\Http\Filters\WppMessages\MessagesByStatus;
use App\Http\Models\WppMessage;
use App\Http\Models\WppSession;
use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Fields\TextArea;
use marcusvbda\vstack\Services\Messages;

class MensagensWpp extends Resource
{
	public $model = WppMessage::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Mensagens WhatsApp";
	}

	public function singularLabel()
	{
		return "Mensagem WhatsApp";
	}

	public function icon()
	{
		return "el-icon-s-comment";
	}

	public function beforeListSlot()
	{
		return View("admin.wpp_messages.before_list")->render();
	}

	public function lenses()
	{
		return [
			"Aguardando" => ["field" => "status", "value" => 'waiting'],
			"Enviadas" => ["field" => "status", "value" => 'sent'],
		];
	}

	public function filters()
	{
		$filters[] = new MessagesByStatus();
		return $filters;
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["f_phone"] = ["label" => "Telefone", "sortable_index" => "data->phone"];
		$columns["message_cuted"] = ["label" => "Mensagem", "sortable_index" => "data->telefone"];
		$columns["wpp_session->name"] = ["label" => "Sessão", "sortable_index" => "wpp_session_id"];
		$columns["f_status"] = ["label" => "Status", "sortable_index" => "status"];
		$columns["user->name"] = ["label" => "Autor", "sortable_index" => "user_id"];
		$columns["user->name"] = ["label" => "Autor", "sortable_index" => "user_id"];
		$columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	private function canAccessModule()
	{
		return Auth::user()->canAccessModule("whatsapp");
	}

	public function canViewList()
	{
		return  $this->canAccessModule();
	}

	public function canView()
	{
		return  false;
	}

	public function canCreate()
	{
		return  $this->canAccessModule();
	}

	public function canImport()
	{
		return  $this->canAccessModule();
	}

	public function nothingStoredText()
	{
		return "<h4>Nenhuma mensagem WhatsApp enviada ...</h4>";
	}

	public function nothingStoredSubText()
	{
		return "<span>
			Caso queira, você pode importar mensagens do WhatsApp utilizando o importador de planilhas ou cadastrando uma nova mensagem.
		</span>";
	}

	public function getTableColumns()
	{
		return [
			"telefone",
			"nome",
			"mensagem",
		];
	}

	public function canClone()
	{
		return  false;
	}

	public function canUpdate()
	{
		return false;
	}

	public function canDelete()
	{
		return  $this->canAccessModule();
	}

	public function canDeleteRow($row)
	{
		return $row->status == "waiting";
	}

	public function canExport()
	{
		return false;
	}

	public function importCustomCrudMessage()
	{
		return "Você também pode criar sua própria planilha, lembrando que a colunas telefone são obrigatórias e as demais serão utilizadas como variaveis de substitução na mansagem.";
	}

	public function importCustomMapStep()
	{
		$sessions = WppSession::all();
		return [
			"title" => "Anexo de Arquivos e Seleção de Sessão",
			"subtitle" => "Caso queira anexar algum arquivo, faça isso aqui",
			"template" => "<WppImportMapStep :step_data='step_data' :form='frm' :sessions='" . json_encode($sessions) . "'/>"
		];
	}

	public function sheetImportRow($rows, $params, $importer)
	{
		extract($params);
		$qty = 0;
		$user = Auth::user();

		foreach ($rows as $key => $row_values) {
			if ($key == 0) {
				continue;
			}

			$row_values = $row_values->toArray();
			$new = ["data" => []];

			foreach ($fieldlist as $field) {
				$index = array_search($field, $importer->headers);
				$value = @$row_values[$index];
				if (!$value) {
					continue;
				}
				$new["data"][$field] = $value;
			}
			$new_model = new $resource->model;
			$new["tenant_id"] = $tenant_id;
			$new["polo_id"] = $user->polo_id;
			$new["user_id"] = $user->id;
			$new_model->fill($new);
			$new_model->save();
			$qty++;
		}

		$importer->setResult([
			'success' => true,
			'qty' => $qty
		]);
	}

	public function prepareImportData($data)
	{
		$wpp_file = @$data["wpp_file"];

		if ($wpp_file) {
			if ($wpp_file->getSize() > 50000000) {
				return ["success" => false, "message" => ["type" => "error", "text" => "Arquivo maior do que o permitido..."]];
			}
		}
		$user = Auth::user();

		return ["success" => true, "data" => [
			"user_id" => $user->id,
			"polo_id" => $user->polo_id,
			"session_id" => request()->session_id,
		]];
	}

	public function importRowMethod($data, $extra_data)
	{
		$new_model = $this->getModelInstance();
		$new_model->tenant_id = data_get($data, "tenant_id");
		$new_model->polo_id = data_get($extra_data, "polo_id");
		$new_model->user_id = data_get($extra_data, "user_id");
		$new_model->wpp_session_id = data_get($extra_data, "session_id");
		unset($data["tenant_id"]);
		$new_model->data = $data;
		$new_model->save();
		return $new_model;
	}

	public function fields()
	{
		$fields = [];
		$fields[] = new Text([
			"label" => "Telefone",
			"field" => "telefone",
			"rules" => ["required", "max:255"],
			"mask" => ['+# (##) ####-####', '+## (##) ####-####', '+## (##) #####-####'],
			"description" => "Telefone da Contato"
		]);
		$cards[] = new Card("Contato", $fields);

		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Sessão",
			"description" => "Sessão do whatsApp que enviará a mensagem",
			"required" => true,
			"field" => "wpp_session_id",
			"options" => WppSession::select("id as id", "name as value")->get()
		]);
		$cards[] = new Card("Processamento", $fields);

		$fields = [];
		$fields[] = new TextArea([
			"label" => "Mensagem",
			"description" => "Mensagem que será enviada",
			"required" => true,
			"field" => "mensagem",
			"rows" => 15
		]);
		$cards[] = new Card("Conteúdo", $fields);
		return $cards;
	}

	public function secondCrudBtn()
	{
		return false;
	}

	public function storeMethod($id, $data)
	{
		$user = Auth::user();
		$target = @$id ? $this->getModelInstance()->findOrFail($id) : $this->getModelInstance();
		$target->wpp_session_id = data_get($data, "data.wpp_session_id");
		$target->polo_id = $user->polo_id;
		$target->user_id = $user->id;
		$target->data = data_get($data, "data");
		$target->save();

		// $controller = new ResourceController;
		// $controller->storeUploads($target, $data["upload"]);

		Messages::send("success", "Registro salvo com sucesso !!");
		$route = route('resource.index', ["resource" => $this->id]);
		return ["success" => true, "route" => $route, "model" => $target];
	}
}
