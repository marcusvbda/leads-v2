<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	HtmlEditor,
	Text
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
		return ["title", "body"];
	}

	public function table()
	{
		$columns = [];
		$columns["title"] = ["label" => "Título"];
		$columns["f_url"] = ["label" => "Url", "sortable_index" => "path"];
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
		$fields[] = new Text([
			"label" => "Título",
			"field" => "title",
			"rules" => ["required", "max:255"],
			"description" => "Título da Postagem"
		]);

		$fields[] = new HtmlEditor([
			"label" => "Descrição",
			"field" => "description",
			"rules" => ["required", "max:255"],
			"description" => "Descrição da Postagem"
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

	public function viewListBlade()
	{
		if (Auth::user()->hasRole(["super-admin"])) {
			return parent::viewListBlade();
		}
		return "admin.wiki.index";
	}

	public function nothingStoredText()
	{
		if (Auth::user()->hasRole(["super-admin"])) {
			return parent::nothingStoredText();
		}
		return "<h4>Aguarde, em breve nossa wiki estará completa ...<h4>";
	}
}
