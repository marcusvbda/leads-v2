<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	HtmlEditor,
	Text,
	TextArea,
	HasOneOrMany,
};
use Auth;
use App\Http\Models\WikiPage;
use App\Http\Resources\Polos;
use App\Http\Resources\Objecoes;

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
		// $fields[] = new HasOneOrMany([
		// 	"disabled" => false,
		// 	"label" => "Exames",
		// 	"description" => "Descrição do campo lorem ipsum",
		// 	"required" => true,
		// 	"relation" => "exame",
		// 	"resource" => Modulos::class,
		// 	"limit" => 1,
		// 	"children" => [
		// 		[
		// 			"limit" => 5,
		// 			"relation" => "questions",
		// 			"resource" => Polos::class,
		// 			"children" => [
		// 				[
		// 					"relation" => "alternatives",
		// 					"resource" => Objecoes::class,
		// 				]
		// 			]
		// 		]
		// 	]
		// ]);

		$fields[] = new Text([
			"label" => "Título",
			"field" => "title",
			"rules" => ["required", "max:255"],
			"description" => "Título da Postagem"
		]);

		$fields[] = new TextArea([
			"label" => "Descrição",
			"field" => "description",
			"rules" => ["required", "max:255"],
			"description" => "Descrição da Postagem",
		]);
		$cards[] = new Card("Identificação", $fields);

		$fields = [];
		$fields[] = new HtmlEditor([
			"label" => "Conteúdo da Página",
			"rules" => ["required"],
			"field" => "body",
			"mode" => "webpage",
			"description" => "Oque será exibido ao acessar a página",
			// "blocks" => [
			// 	"hello_world_teste" => [
			// 		"label"  => "Hello World",
			// 		"attributes" =>  [
			// 			"class" => "gjs-fonts gjs-f-text"
			// 		],
			// 		"content" =>  "<h1>Hello World TESTE</h1>"
			// 	]
			// ]
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
