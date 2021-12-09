<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	TextArea,
};
use App\Http\Models\LandingPage;

class LandingPages extends Resource
{
	public $model = LandingPage::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Landing Pages";
	}

	public function singularLabel()
	{
		return "Landing Page";
	}

	public function icon()
	{
		return "el-icon-star-on";
	}

	public function search()
	{
		return ["name"];
	}

	public function lenses()
	{
		return [
			"Publicadas" => ["field" => "published", "value" => true],
			"Rascunhos" => ["field" => "published", "value" => false],
		];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "#", "sortable_index" => "id"];
		$columns["label"] = ["label" => "Nome", "sortable_index" => "name"];
		$columns["f_url"] = ["label" => "Url", "sortable_index" => "path"];
		$columns["f_created_at_badge"] = ["label" => "Data de Criação", "sortable_index" => "created_at"];
		$columns["f_updated_at_badge"] = ["label" => "Data de Edição", "sortable_index" => "updated_at"];
		$columns["stats_btn"] = ["label" => "", "sortable" => false, "width" => "10%"];
		return $columns;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-landing-pages");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-landing-pages");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-landing-pages");
	}

	public function canView()
	{
		return false;
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
		return hasPermissionTo("viewlist-landing-pages");
	}

	private function isCreating()
	{
		return request()->page_type === "create";
	}

	public function createBlade()
	{
		return "admin.landing-pages.templates";
	}

	public function editBlade()
	{
		return "admin.landing-pages.editor";
	}

	public function fields()
	{
		$cards = [];
		$is_creating = $this->isCreating();

		$fields = [];
		$fields = [
			new Text([
				"label" => "Nome",
				"description" => "Apenas para indentificação da landing page",
				"field" => "name",
				"rules" => ["required", "max:255"]
			]),
			new Text([
				"label" => "Títula da Página",
				"description" => "Importante para o SEO",
				"field" => "title",
				"rules" => $is_creating ? [] : ["max:255"]
			]),
			new TextArea([
				"label" => "Descrição da Página",
				"description" => "Importante para o SEO",
				"field" => "description",
				"rules" => $is_creating ? [] : ["max:500"]
			]),
		];
		$cards[] = new Card("Informações", $fields);

		return $cards;
	}
}
