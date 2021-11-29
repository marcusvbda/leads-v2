<?php

namespace App\Http\Resources;

use App\Http\Models\UserIntegrator;
use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check,
	CustomComponent,
	Radio
};
use Auth;

class Integracoes extends Resource
{
	public $model = UserIntegrator::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Integrações";
	}

	public function singularLabel()
	{
		return "Integração";
	}

	public function icon()
	{
		return "el-icon-wind-power";
	}

	public function search()
	{
		return ["name"];
	}

	private function isHead()
	{
		return @Auth::user()->polo->head ?? false;
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "#", "sortable_index" => "id"];
		$columns["label"] = ["label" => "Descrição"];
		$columns["f_env"] = ["label" => "Env", "sortable_index" => "env"];
		$columns["key"] = ["label" => "Key"];
		return $columns;
	}

	public function canCreate()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"]) && $this->isHead();
	}

	public function canUpdate()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function canDelete()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function canClone()
	{
		return false;
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}

	public function canViewList()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function canView()
	{
		return  false;
	}

	public function fields()
	{
		$default_secret = md5(uniqid());
		$default_key = uniqid();

		$fields[] = new Check([
			"label" => "Habilitado",
			"field" => "enabled",
			"default" => false,
			"rules" => ["required", "max:255"]
		]);
		$fields[] = new Text([
			"label" => "Nome",
			"field" => "name",
			"rules" => ["required", "max:255"]
		]);
		$fields[] = new Radio([
			"label" => "Status Operacional",
			"description" => "Define o modo que este integrador está",
			"field" => "env",
			"default" => "homologation",
			"options" => UserIntegrator::_ENV_OPTIONS_,
			"rules" => ["required", "max:255"]
		]);
		$cards[] = new Card("Informações Básicas", $fields);

		$fields = [];
		$fields[] = new Text([
			"label" => "Key",
			"field" => "key",
			"default" => $default_key,
			"rules" => "max:255",
			"disabled" => true,
			"rules" => ["required", "max:255"]

		]);
		$fields[] = new Text([
			"label" => "Secret",
			"field" => "secret",
			"type" => "password",
			"default" => $default_secret,
			"disabled" => true,
			"rules" => ["required", "max:255"]
		]);

		if (request("page_type") == "edit") {
			$content = request("content");
			$fields[] = new Text([
				"label" => "Auth Token",
				"field" => "token",
				"description" => "Use o login e senha como basic auth ou passe este token para o header Authorization",
				"type" => "password",
				"disabled" => true,
				"required" => true
			]);
		}
		$cards[] = new Card("Autenticação", $fields);
		return $cards;
	}

	public function storeMethod($id, $data)
	{
		unset($data["data"]["token"]);
		return parent::storeMethod($id, $data);
	}
}
