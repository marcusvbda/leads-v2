<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check
};

class Objecoes extends Resource
{
	public $model = \App\Http\Models\Objection::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Objeção de Contato";
	}

	public function singularLabel()
	{
		return "Objeções de Contatos";
	}

	public function icon()
	{
		return "el-icon-error";
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
		$columns["f_need_description"] = ["label" => "Descrição Obrigatória", "sortable_index" => "need_description"];
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
				"label" => "Descrição",
				"field" => "description",
				"required" => true,
				"rules" => "max:255"
			]),
			new Check([
				"label" => "Solicitar Detalhes",
				"description" => "O campo de detalhes da objeção será obrigatório para seguir com o atendimento",
				"field" => "need_description",
				"default" => false
			])
		];
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}
}