<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check
};
use Auth;

class Webhooks extends Resource
{
	public $model = \App\Http\Models\Webhook::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Webhooks";
	}

	public function singularLabel()
	{
		return "Webhook";
	}

	public function icon()
	{
		return "el-icon-finished";
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
		$columns["url"] = ["label" => "Url", "sortable_index" => "token"];
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
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function fields()
	{
		$fields = [
			new Text([
				"label" => "Nome",
				"field" => "name",
				"required" => true,
				"rules" => "max:255"
			]),
			new Text([
				"label" => "Token",
				"field" => "token",
				"required" => true,
				"disabled" => true,
				"default" => md5(uniqid()),
				"rules" => "max:255"
			]),
			new Check([
				"label" => "Habilitado",
				"field" => "enabled",
				"default" => true
			])
		];
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}

	public function afterViewSlot()
	{
		$webhook = request("content");
		$data = $webhook->requests()->orderBy("id", "desc")->paginate(10);
		$settings = $webhook->settings;
		$resource = $this;
		$tenant_id = Auth::user()->tenant_id;
		return view("admin.webhooks.requests", compact("data", "resource", "webhook", "tenant_id", "settings"));
	}
}