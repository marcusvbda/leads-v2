<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check
};

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
		return hasPermissionTo("create-objections");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-objections");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-objections");
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
		return hasPermissionTo("viewlist-objections");
	}

	public function canView()
	{
		return false;
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
}