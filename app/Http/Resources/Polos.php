<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check,
};

class Polos extends Resource
{
	public $model = \App\Http\Models\Polo::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Polos";
	}

	public function singularLabel()
	{
		return "Polo";
	}

	public function icon()
	{
		return "el-icon-s-shop";
	}

	public function search()
	{
		return ["name", "razao_social"];
	}

	public function table()
	{
		$columns = [];
		$columns["name"] = ["label" => "Nome"];
		$columns["city"] = ["label" => "Cidade"];
		$columns["f_head"] = ["label" => "Sede"];
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
			new Text([
				"label" => "Cidade",
				"field" => "city",
				"required" => true,
				"rules" => "max:255"
			]),
			new Check([
				"label" => "Sede",
				"field" => "head"
			])

		];
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}
}