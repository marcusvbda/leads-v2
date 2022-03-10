<?php

namespace App\Http\Resources;

use App\Http\Models\WppMessage;
use marcusvbda\vstack\Resource;
use Auth;

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

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["f_phone"] = ["label" => "Telefone", "sortable_index" => "data->phone"];
		$columns["message_cuted"] = ["label" => "Mensagem", "sortable_index" => "data->telefone"];
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
		return  false;
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
		return "<span>Caso queira, você pode importar mensagens do WhatsApp utilizando o importador de planilhas.</span>";
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
		return false;
	}

	public function importCustomMapStep()
	{
		return [
			"title" => "Anexo de Arquivos",
			"subtitle" => "Caso queira anexar algum arquivo, faça isso aqui",
			"template" => "<WppImportMapStep :step_data='step_data' :form='frm' />"
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
			dd(request()->all());

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

	// public function importMethod($data, $file)
	// {
	// 	$wpp_file = @$data["wpp_file"];
	// 	if ($wpp_file) {
	// 		if ($wpp_file->getSize() > 50000000) {
	// 			return ["success" => false, "message" => ["type" => "error", "text" => "Arquivo maior do que o permitido..."]];
	// 		}
	// 	}

	// 	$config = json_decode($data["config"]);
	// 	$fieldlist = $config->fieldlist;

	// 	$user = Auth::user();
	// 	$tenant_id = $user->tenant_id;
	// 	$file_extension = Vstack::resource_export_extension();
	// 	$filename = $user->tenant_id . "_" . uniqid() . "." . $file_extension;
	// 	$filepath = $file->storeAs('local', $filename);
	// 	$resource = $this;

	// 	$importer = new GlobalImporter($filepath, self::class, 'sheetImportRow', compact('resource', 'fieldlist', 'filepath', 'tenant_id'));
	// 	Excel::import($importer, $importer->getFile());
	// 	$result = $importer->getResult();

	// 	// dispatch(function () use ($resource, $fieldlist, $filepath, $tenant_id, $user) {
	// 	$importer = new GlobalImporter($filepath, self::class, 'sheetImportRow', compact('resource', 'fieldlist', 'filepath', 'tenant_id'));
	// 	Excel::import($importer, $importer->getFile());
	// 	$result = $importer->getResult();
	// 	unlink(storage_path("app/" . $filepath));

	// 	if (@$result["success"]) {
	// 		$message = "Foi importado com sucesso sua planilha de " . $resource->label() . ". (" . $result['qty'] . " Registro" . ($result['qty'] > 1 ? 's' : '') . ")";
	// 	} else {
	// 		$message = "Erro na importação de planilha de " . $resource->label() . " ( " . $result["error"]['message'] . " )";
	// 	}
	// 	DB::table("notifications")->insert([
	// 		"type" => 'App\Notifications\CustomerNotification',
	// 		"notifiable_type" => 'App\User',
	// 		"notifiable_id" => $user->id,
	// 		"alert_type" => 'vstack_alert',
	// 		"tenant_id" => $user->tenant_id,
	// 		"created_at" => Carbon::now(),
	// 		"data" => json_encode([
	// 			"message" => $message,
	// 			"type" => @$result["success"] ? 'success' : 'error'
	// 		]),
	// 	]);
	// 	// })->onQueue(Vstack::queue_resource_import());

	// 	return ["success" => true];
	// }

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
		]];
	}

	public function importMethod($data, $extra_data)
	{
		$new_model = $this->getModelInstance();
		$new_model->tenant_id = data_get($data, "tenant_id");
		$new_model->polo_id = data_get($extra_data, "polo_id");
		$new_model->user_id = data_get($extra_data, "user_id");
		unset($data["tenant_id"]);
		$new_model->data = $data;
		$new_model->save();
		return $new_model;
	}
}
