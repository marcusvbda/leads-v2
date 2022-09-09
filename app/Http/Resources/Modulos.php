<?php

namespace App\Http\Resources;

use App\Http\Models\Polo;
use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
	BelongsTo,
	Card,
	Text,
	Check,
};

class Modulos extends Resource
{
	public $model = \App\Http\Models\Module::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Modulos";
	}

	public function singularLabel()
	{
		return "Modulo";
	}

	public function icon()
	{
		return "el-icon-copy-document";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["name"] = ["label" => "Nome"];
		$columns["f_new_badge"] = ["label" => "Mostrar cartão de 'novo'", "sortable_index" => "mew_badge"];
		$columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	public function canClone()
	{
		return false;
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
		return false;
	}


	public function fields()
	{
		$fields[] = new Text([
			"label" => "Nome",
			"field" => "name",
			"description" => "Para identificação do módulo",
			"required" => true,
			"rules" => "max:255"
		]);
		$fields[] = new Text([
			"label" => "Slug",
			"field" => "slug",
			"description" => "Para identificação do módulo",
			"required" => true,
			"rules" => "max:255"
		]);
		$cards[] = new Card("Informações Básicas", $fields);

		$fields = [];
		$fields[] = new Check([
			"label" => "habilitar cartão de 'Novo'",
			"field" => "new_badge",
			"default" => true,
			"required" => true
		]);
		$fields[] = new BelongsTo([
			"label" => "Polos Habilitados",
			"field" => "polo_ids",
			"model" => Polo::class,
			"multiple" => true,
		]);
		$cards[] = new Card("Configurações", $fields);

		return $cards;
	}
}
