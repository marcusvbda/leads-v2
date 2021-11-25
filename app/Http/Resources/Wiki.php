<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Check,
	CustomComponent,
	HtmlEditor,
	Text,
};
use Auth;
use App\Http\Models\WikiPage;

class Wiki extends Resource
{
	public $model = WikiPage::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Base de Conhecimento";
	}

	public function indexLabel()
	{
		return "<span class='" . $this->icon() . " mr-2'></span>" . $this->label();
	}

	public function singularLabel()
	{
		return $this->label();
	}

	public function icon()
	{
		return "el-icon-s-opportunity";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["title"] = ["label" => "Título"];
		$columns["f_cover"] = ["label" => "Capa", "sortable_index" => "cover"];
		$columns["url"] = ["label" => "Url", "sortable_index" => "path"];
		$columns["f_created_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	public function canCreate()
	{
		return  Auth::user()->hasRole(["super-admin"]);
	}

	public function canUpdate()
	{
		return  Auth::user()->hasRole(["super-admin"]);
	}

	public function canDelete()
	{
		return  Auth::user()->hasRole(["super-admin"]);
	}

	public function canClone()
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
		return  true;
	}

	public function canView()
	{
		return  false;
	}

	public function fields()
	{
		$fields[] = new Check([
			"label" => "Capa",
			"field" => "cover",
			"rules" => ["required", "max:255"],
			"description" => "Primeiro post da wiki a aparecer para o usuário, caso nenhuma capa for selecionada, o registro mais antigo será considerado"
		]);
		$fields[] = new Text([
			"label" => "Título",
			"field" => "title",
			"rules" => ["required", "max:255"],
			"description" => "Título da Postagem"
		]);
		$cards[] = new Card("Identificação", $fields);

		$fields = [];
		$fields[] = new HtmlEditor([
			"label" => "Conteúdo da Página",
			"rules" => ["required"],
			"field" => "body",
		]);
		$cards[] = new Card("Conteúdo", $fields);

		return $cards;
	}
}
