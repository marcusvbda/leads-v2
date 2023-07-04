<?php

namespace App\Http\Resources;

use App\Http\Models\Pivots\UserPolo;
use App\Http\Models\Polo;
use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
};

class Empresas extends Resource
{
	public $model = Polo::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Empresas";
	}

	public function singularLabel()
	{
		return "Empresa";
	}

	public function icon()
	{
		return "el-icon-s-shop";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["name"] = ["label" => "Nome"];
		$columns["city"] = ["label" => "Cidade"];
		$columns["qty_users"] = [
			"label" => "Usuários com acesso",
			"handler" => function ($row) {
				return UserPolo::where("polo_id", $row->id)->count();
			},
			"sortable" => false
		];
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
		];
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}
}
