<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use App\Http\Models\{Status, CustomAutomation, Email};
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Radio,
	BelongsTo
};

class AutomacoesCustomizadas extends Resource
{
	public $model = CustomAutomation::class;

	public function label()
	{
		return "Automações Customizadas";
	}

	public function singularLabel()
	{
		return "Automação Customizada";
	}

	public function icon()
	{
		return "el-icon-setting";
	}

	public function search()
	{
		return ["name"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id", "size" => "100px"];
		$columns["name"] = ["label" => "Nome", "sortable_index" => "name"];
		$columns["status_name"] = ["label" => "Status", "sortable" => false];
		$columns["f_trigger"] = ["label" => "Gatilho", "sortable_index" => "data->trigger"];
		$columns["f_email_template"] = ["label" => "Template de Email", "sortable" => false];

		return $columns;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-automation");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-automation");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-automation");
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-automation");
	}

	public function canView()
	{
		return false;
	}

	public function canViewReport()
	{
		return hasPermissionTo("report-automation");
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return hasPermissionTo("report-automationn");
	}


	public function export_columns($cx)
	{
		return [
			"code" => ["label" => "Código"],
			"name" => ["label" => "Nome"],
			"status_name" => ["label" => "Status"],
			"f_trigger" => ["label" => "Gatilho"],
			"f_email_template" => ["label" => "Template de Email"],
		];
	}

	public function fields()
	{
		$fields = [
			new Card("Identificação", [
				new Text([
					"label" => "Nome",
					"description" => "Esta informação será usada apenas para identificação da parametrização desta automação",
					"field" => "name",
					"rules" => ["required", "max:255"]
				])
			]),
			new Card("Lógica", [
				new Radio([
					"label" => "Status",
					"description" => "Para quais status de lead o gatilho será acionado",
					"field" => "status_id",
					"required" => true,
					"options" => Status::selectRaw("id as value,name as label")->get()
				]),
				new Radio([
					"label" => "Gatilho",
					"description" => "Quando esta automação deverá ser acionada",
					"field" => "trigger",
					"required" => true,
					"options" => CustomAutomation::_TRIGGERS_
				]),
			]),
			new Card("Mensagem", [
				new BelongsTo([
					"label" => "Modelo de Email",
					"description" => "Qual modelo de email deverá ser enviado quando esta automação for acionada",
					"field" => "email_template_id",
					"required" => true,
					"model" => Email::class
				]),
			])
		];
		return $fields;
	}
}