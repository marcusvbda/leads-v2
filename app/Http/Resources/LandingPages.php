<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Radio,
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

	private function actionHTMLOption($index)
	{
		$types = [
			"redirect" => [
				"label" => "Redirecionamento da página",
				"icon" => "el-icon-document-checked"
			],
			"download" => [
				"label" => "Download de arquivo",
				"icon" => "el-icon-download"
			],
			"none" => [
				"label" => "Nenhuma ação",
				"icon" => "el-icon-circle-close"
			]
		];
		$type = $types[$index];
		return '<div class="d-flex flex-column align-items-center justify-content-center">
					<span class="mb-2">' . $type['label'] . '</span>
					<h2><i class="' . $type['icon'] . '"></i></h2>
				</div>';
	}

	public function fields()
	{
		$cards = [];
		$is_creating = $this->isCreating();

		$fields = [
			new Radio([
				"label" => "Ação",
				"description" => "Quando o visitante clicar no CTA, o que deverá acontecer ?",
				"field" => "action",
				"default" => "none",
				"options" => [
					["value" => "redirect", "label" => $this->actionHTMLOption('redirect')],
					["value" => "download", "label" => $this->actionHTMLOption('download')],
					["value" => "none", "label" => $this->actionHTMLOption('none')]
				],
				"rules" => $is_creating ? [] : ["required", "max:255"]
			]),
			new Text([
				"label" => "Página de Agradecimento",
				"description" => "O usuário será direcionado para está URL após clicar no CTA",
				"field" => "action_url",
				"eval" => "v-if='form.action == `redirect`'",
				"rules" => ["max:255", function ($attr, $val, $fail) use ($is_creating) {
					if (!$is_creating && request()->action == 'redirect' && !$val) {
						return $fail("Url de redirecionamento é obrigatório");
					}
				}],
			]),
			new Text([
				"label" => "Url de Download",
				"description" => "O usuário fará automáticamente o download apartir desta URL após clicar no CTA",
				"field" => "download_url",
				"eval" => "v-if='form.action == `download`'",
				"rules" => ["max:255", function ($attr, $val, $fail) use ($is_creating) {
					if (!$is_creating && request()->action == 'download' && !$val) {
						return $fail("Url de download é obrigatório");
					}
				}],
			]),
		];
		$cards[] = new Card("Ações do Formulário", $fields);

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
				"rules" => $this->isCreating() ? [] : ["max:255"]
			]),
			new TextArea([
				"label" => "Descrição da Página",
				"description" => "Importante para o SEO",
				"field" => "description",
				"rules" => $this->isCreating() ? [] : ["max:500"]
			]),
		];
		$cards[] = new Card("Informações", $fields);
		return $cards;
	}

	public function storeMethod($id, $data)
	{
		if ($data["data"]["action"] != "redirect") {
			$data["data"]["action_url"] = null;
		}
		if ($data["data"]["action"] != "download") {
			$data["data"]["download_url"] = null;
		}
		return parent::storeMethod($id, $data);
	}
}
