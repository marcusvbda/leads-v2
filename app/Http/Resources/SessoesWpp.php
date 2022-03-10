<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	CustomComponent,
	Text,
	TextArea
};
use Auth;
use App\Http\Models\WppSession;
use marcusvbda\vstack\Services\Messages;

class SessoesWpp extends Resource
{
	public $model = WppSession::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Sessões WhatsApp";
	}

	public function indexLabel()
	{
		return "<span class='" . $this->icon() . " mr-2'></span>" . $this->label();
	}

	public function singularLabel()
	{
		return "Sessão WhatsApp";
	}

	public function icon()
	{
		return "el-icon-s-opportunity";
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["name"] = ["label" => "Nome"];
		$columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	private function canAccessModule()
	{
		return Auth::user()->canAccessModule("WhatsApp");
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

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}

	public function fields()
	{
		$fields = [];
		$fields[] = new Text([
			"label" => "Nome",
			"field" => "name",
			"rules" => ["required", "max:255"],
			"description" => "Nome da Sessão"
		]);
		$cards[] = new Card("Identificação", $fields);

		$fields = [];
		$is_creating = $this->isCreating();
		if ($is_creating) {
			$fields[] = new CustomComponent("<InputQrCode :form='form' :data='data' :errors='errors' field_index='auth' />", [
				"label" => "Código QR",
				"field" => "auth",
				"description" => "Escaneie o código QR",
				"_uid" => "qrcode",
			]);
		}
		$fields[] = new Text([
			"label" => "Token",
			"description" =>  $is_creating ? "" : "Escaneie o código QR para obter o token",
			"field" => "string_token",
			"rules" => ["required"],
			"disabled" => true,
			"rows" => 10,
		]);
		$cards[] = new Card("Autenticação", $fields, [
			"_uid" => "auth_card"
		]);
		return $cards;
	}

	private function isCreating()
	{
		return request()->page_type == 'create';
	}

	public function secondCrudBtn()
	{
		return false;
	}

	public function storeMethod($id, $data)
	{
		$target = @$id ? $this->getModelInstance()->findOrFail($id) : $this->getModelInstance();
		$target->name  = data_get($data, "data.name");
		$model_data = [
			"token" =>  json_decode(data_get($data, "data.string_token"))
		];
		$target->data = $model_data;
		$target->save();
		Messages::send("success", "Registro salvo com sucesso !!");
		$route = route('resource.index', ["resource" => $this->id]);
		return ["success" => true, "route" => $route, "model" => $target];
	}
}
