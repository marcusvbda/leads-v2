<?php

namespace App\Http\Resources;

use App\Http\Models\Campaign;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Tags;
use marcusvbda\vstack\Fields\Text;
use marcusvbda\vstack\Resource;

class Campanhas extends Resource
{
	public $model = Campaign::class;

	public function label()
	{
		return "Campanhas";
	}

	public function singularLabel()
	{
		return "Campanha";
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
		$columns["label"] = ["label" => "Nome", "sortable_index" => "name"];
		$columns["user->name"] = ["label" => "Autor", "sortable_index" => "user_id"];
		$columns["f_updated_at_badge"] = ["label" => "Data", "sortable_index" => "created_at", "size" => "200px"];
		return $columns;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-campaign");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-campaign");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-campaign");
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-campaign");
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

		$fields = [];
		$fields[] = new BelongsTo([
			"label" => "Campos do formulário",
			"description" => "Campos do formulário de captação de leads",
			"field" => "fields",
			"rules" => ["required"],
			"options" => Leads::getFieldListOption(),
			"multiple" => true,
		]);
		$cards[] = new Card("Captação", $fields);

		$fields = [];
		$fields[] = new Tags([
			"label" => "Fases",
			"description" => "Fases do funil",
			"field" => "stages",
			"rules" => ["required"],
		]);
		$cards[] = new Card("Funil", $fields);
		return $cards;
	}
}
