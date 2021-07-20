<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check,
};

class Departamentos extends Resource
{
	public $model = \App\Http\Models\Department::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Departamentos";
	}

	public function singularLabel()
	{
		return "Departamento";
	}

	public function icon()
	{
		return "el-icon-s-comment";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["name"] = ["label" => "Nome"];
		$columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	public function canCreate()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canUpdate()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canDelete()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
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
		return Auth::user()->hasRole(["super-admin", "admin"]);
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
		];
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}
}