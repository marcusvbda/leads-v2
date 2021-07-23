<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	HtmlEditor
};
use App\Http\Models\{Email, EmailTemplate};

class Emails extends Resource
{
	public $model = Email::class;

	public function label()
	{
		return "Emails";
	}

	public function singularLabel()
	{
		return "Email";
	}

	public function icon()
	{
		return "el-icon-s-promotion";
	}

	public function search()
	{
		return ["name", "subject"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id", "size" => "100px"];
		$columns["name"] = ["label" => "Nome"];
		$columns["subject"] = ["label" => "Assunto"];
		return $columns;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-email");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-email");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-email");
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-email");
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

	public function export_columns($cx)
	{
		return [
			"code" => ["label" => "Código"],
			"name" => ["label" => "Nome"],
			"subject" => ["label" => "Assunto"],
		];
	}

	public function fields()
	{
		$content = request("content");
		$default = (object)["css" => "", "body" => ""];
		if (!@$content->id && @request("template")) {
			$template = EmailTemplate::where("slug", request("template"))->first();
			$default = $template ? $template->body : (object)["css" => "", "body" => ""];
		}
		return [
			new Card("Identificação", [
				new Text([
					"label" => "Nome",
					"field" => "name",
					"rules" => ["required", "max:255"],
				]),
			]),
			new Card("Email", [
				new Text([
					"label" => "Assunto",
					"field" => "subject",
					"rules" => ["required", "max:255"]
				]),
				// new HtmlEditor([
				// 	"label" => "Corpo do Email",
				// 	"field" => "body",
				// 	"mode" => "newsletter",
				// 	"default" => $default
				// ]),
			]),
		];
	}

	public function beforeCreateSlot()
	{
		return view('admin.emails.crud_alert')->render();
	}

	public function beforeEditSlot()
	{
		return view('admin.emails.crud_alert')->render();
	}

	public function createMethod($params, $data)
	{
		if (!@request()->has("template")) {
			$templates = EmailTemplate::get();
			return view("admin.emails.select_templates", compact("templates"));
		}
		return parent::createMethod($params, $data);
	}
}