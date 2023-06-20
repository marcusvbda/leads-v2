<?php

namespace App\Http\Resources;

use App\Http\Models\Funnel;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;

class Funis extends Resource
{
	public $model = Funnel::class;

	public function label()
	{
		return "Funis";
	}

	public function singularLabel()
	{
		return "Funil";
	}

	public function icon()
	{
		return "el-icon-pie-chart";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id", "size" => "100px"];
		$columns["name"] = ["label" => "Nome"];
		$columns["user->name"] = ["label" => "Autor", "sortable_index" => "user_id"];
		$columns["f_updated_at_badge"] = ["label" => "Data", "sortable_index" => "created_at", "size" => "200px"];
		return $columns;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-funnel");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-funnel");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-funnel");
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-funnel");
	}

	public function canView()
	{
		return false;
	}

	public function canViewReport()
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

	public function fields()
	{
		$cards = [];
		$fields = [];
		$fields[] = new Text([
			"label" => "Nome",
			"field" => "name",
			"rules" => ["required", "max:255"],
		]);
		$cards[] = new Card("Informações Básicas", $fields);
		return $cards;
	}
}
