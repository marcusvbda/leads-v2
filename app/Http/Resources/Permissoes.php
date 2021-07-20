<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;

use marcusvbda\vstack\Fields\{Text, Card};

class Permissoes extends Resource
{
	public $model = \App\Http\Models\Permission::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Permissões";
	}

	public function singularLabel()
	{
		return "Permissão";
	}

	public function icon()
	{
		return "el-icon-lock";
	}

	public function search()
	{
		return ["name", "description", "group"];
	}

	public function fields()
	{
		return [
			new Card("Informações", [
				new Text([
					"label" => "Agrupamento da Permissão",
					"field" => "group",
				]),
				new Text([
					"label" => "Nome",
					"field" => "name",
					"required" => true,
					"description" => "De preferência a letras minúsculas sem espaço",
					"rules" => "max:255"
				]),
				new Text([
					"label" => "Descrição",
					"field" => "description",
					"required" => true,
					"rules" => "max:255"
				]),
			])
		];
	}

	public function table()
	{
		$columns = [];
		$columns["description"] = ["label" => "Nome"];
		$columns["group"] = ["label" => "Grupo"];
		$columns["name"] = ["label" => "Valor"];
		$columns["f_created_at_for_humans"] = ["label" => "", "sortable_index" => "created_at"];
		return $columns;
	}

	public function canCreate()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canUpdate()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canDelete()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canCustomizeMetrics()
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
		return Auth::user()->hasRole(["super-admin"]);
	}

	public function canView()
	{
		return Auth::user()->hasRole(["super-admin"]);
	}
}