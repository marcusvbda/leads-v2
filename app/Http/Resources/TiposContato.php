<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Radio,
	Text,
};

class TiposContato extends Resource
{
	public $model = \App\Http\Models\ContactType::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Tipos de Contato";
	}

	public function singularLabel()
	{
		return "Tipos de Contato";
	}

	public function icon()
	{
		return "el-icon-menu";
	}

	public function search()
	{
		return ["description"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "#", "sortable_index" => "id"];
		$columns["description"] = ["label" => "Descrição"];
		return $columns;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-contacttype");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-contacttype");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-contacttype");
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
		return hasPermissionTo("viewlist-contacttype");
	}

	public function canView()
	{
		return false;
	}

	public function fields()
	{
		$actions = [];
		$actions[] = ["value" => "none", "label" => "Sem ação"];
		$actions[] = ["value" => "send-email", "label" => "Enviar Email"];

		$fields = [
			new Text([
				"label" => "Descrição",
				"field" => "description",
				"required" => true,
				"rules" => "max:255"
			]),
		];

		$fields[] = new Radio([
			"label" => "Ação",
			"description" => "Ação adicional da seleção desta resposta",
			"field" => "action",
			"default" => "none",
			"options" => $actions
		]);
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}
}
