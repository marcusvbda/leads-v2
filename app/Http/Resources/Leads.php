<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use App\Http\Models\{Lead};
use App\Http\Filters\Leads\{
	LeadsByName,
	LeadsByCreatedDate,
	LeadsByStatus,
};
use App\Http\Filters\{FilterByTags};
use App\Http\Actions\Leads\{
	LeadTransfer,
};
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	TextArea,
};

class Leads extends Resource
{

	public $model = Lead::class;
	public $_filters = [];

	public function __construct()
	{
		$this->_filters = [
			new LeadsByCreatedDate(),
			new LeadsByName(),
			new LeadsByStatus(),
			new FilterByTags(Lead::class)
		];
		parent::__construct();
	}

	public function label()
	{
		return "Leads";
	}

	public function resultsPerPage()
	{
		return [20, 50, 100, 200, 500];
	}

	public function canClone()
	{
		return true;
	}

	public function singularLabel()
	{
		return "Lead";
	}

	public function icon()
	{
		return "el-icon-trophy";
	}

	public function search()
	{
		return ["data->name", "data->email"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id", "size" => "100px"];
		$columns["name"] = ["label" => "Nome", "sortable_index" => "data->name"];
		$columns["f_status_badge"] = ["label" => "Status", "sortable_index" => "status_id"];
		$columns["email_url"] = ["label" => "Email", "sortable_index" => "data->email"];
		$columns["f_rating"] = ["label" => "Classificação", "sortable" => false];
		$columns["f_updated_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	public function actions()
	{
		return [
			new LeadTransfer()
		];
	}


	public function canCreate()
	{
		return hasPermissionTo("create-leads");
	}

	public function canUpdate()
	{
		return hasPermissionTo("edit-leads");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-leads");
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-leads");
	}

	public function canView()
	{
		return false;
	}

	public function canViewReport()
	{
		return hasPermissionTo("view-leads-report");
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return hasPermissionTo("view-leads-report");
	}


	public function export_columns($cx)
	{
		$fields = [
			"code" => ["label" => "Código"],
			"name" => ["label" => "Nome"],
			"status->name" => ["label" => "Status"],
			"profession" => ["label" => "Profissão"],
			"email" => ["label" => "Email"],
			"phones" => ["label" => "Telefones", "handler" => function ($row) {
				return implode(" - ", $row->data->phones);
			}],
			"interest" => ["label" => "Interesse"],
			"data" => ["label" => "Data", "handler" => function ($row) {
				return formatDate($row->created_at);
			}],
		];
		return $fields;
	}

	public function filters()
	{
		return $this->_filters;
	}

	public function fields()
	{
		$fields = [
			"Identificação" => [
				new Text([
					"label" => "Nome Completo",
					// "description" => "lorem ipsum lorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsumlorem ipsum",
					"field" => "name",
					"rules" => ["required", "max:255"]
				]),
				new Text([
					"type" => "date",
					"label" => "Data de Nascimento",
					"field" => "birthdate",
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Profissão",
					"field" => "profession",
					"rules" => ["max:255"]
				]),
				new TextArea([
					"label" => "Interesse",
					"field" => "interest",
					"rows" => 10,
					"rules" => ["max:255"]
				]),
			],
			"Contato" => [
				new Text([
					"label" => "Email",
					"field" => "email",
					"rules" => ["nullable", "max:255", "email"]
				]),
				new Text([
					"label" => "Telefone",
					"field" => "phone_number",
					"mask" => ["(##) ####-####", "(##) #####-####"],
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Celular",
					"field" => "cellphone_number",
					"mask" => ["(##) ####-####", "(##) #####-####"],
					"rules" => ["max:255"]
				]),
			],
			"Localidade" => [
				new Text([
					"label" => "Cep",
					"field" => "zipcode",
					"mask" => ["#####-###"],
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Cidade",
					"field" => "city",
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Bairro",
					"field" => "district",
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Número",
					"field" => "address_number",
					"rules" => ["max:255"]
				]),
				new Text([
					"label" => "Complemento",
					"field" => "complementary",
					"rules" => ["max:255"]
				]),
			],
			"Extras" => [
				new TextArea([
					"label" => "Comentários",
					"field" => "comment",
					"rows" => 10,
					"rules" => ["max:255"]
				]),
				new TextArea([
					"label" => "Observações",
					"field" => "obs",
					"rows" => 10,
					"rules" => ["max:255"]
				]),
			],
		];

		$cards = [];
		foreach ($fields as $key => $value) {
			$cards[] = new Card($key, $value);
		}
		return $cards;
	}

	public function useTags()
	{
		return true;
	}

	public function tableAfterRow($row)
	{
		return '<identification-row lead_id="' . $row->id . '" />';
	}

	public function crudType()
	{
		return [
			"template" => request()->get("content") ? "page" : "wizard"
		];
	}
}