<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use App\Http\Models\Lead;
use App\Http\Actions\Leads\{
	LeadStatusChange,
	LeadDelete,
	LeadRemoveDuplicates,
	LeadReprocess,
	LeadSourceReprocess,
	LeadTransfer,
};
use App\Http\Filters\FilterByPresetDate;
use App\Http\Filters\FilterByTags;
use App\Http\Filters\FilterByText;
use App\Http\Filters\Leads\LeadsByPhone;
use App\Http\Filters\Leads\LeadsByStatus;
use App\Http\Models\Status;
use marcusvbda\vstack\Fields\{
	BelongsTo,
	Card,
	Text,
	TextArea,
};
use Auth;

class Leads extends Resource
{

	public $model = Lead::class;


	public function label()
	{
		return "Leads";
	}

	public function resultsPerPage()
	{
		return [20, 50, 100, 200, 500];
	}

	public function maxRowsExportSync()
	{
		return 1000;
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
		$columns["label"] = ["label" => "Nome", "sortable_index" => "data->name"];
		$columns["contact"] = ["label" => "Email", "sortable_index" => "data->email"];
		$columns["f_status_badge"] = ["label" => "Status", "sortable_index" => "status_id"];
		$columns["responsible->name"] = ["label" => "Responsável", "sortable_index" => "responsible_id"];
		$columns["f_updated_at_badge"] = ["label" => "Data", "sortable_index" => "created_at"];
		return $columns;
	}

	public function loadListItemByItem()
	{
		return false;
	}

	public function actions()
	{
		$user = Auth::user();
		$is_super_admin_or_admin = $user->hasRole(["super-admin", "admin"]);
		$actions = [];
		if (hasPermissionTo("edit-leads")) {
			$actions[] = new LeadStatusChange();
			$actions[] = new LeadTransfer();
		}
		if (hasPermissionTo("destroy-leads")) {
			$actions[] = new LeadDelete();
		}
		if ($is_super_admin_or_admin) {
			$actions[] = new LeadSourceReprocess();
			$actions[] = new LeadReprocess();
			$actions[] = new LeadRemoveDuplicates();
		}
		return $actions;
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
		// dd(request()->page_type);
		$fields["code"] = ["label" => "Código"];
		$fields["name"] = ["label" => "Nome"];
		$fields["origins"] = ["label" => "Origens", "handler" => function ($row) {
			return implode(", ", @$row->data->source ?? []);
		}];
		$fields["status->name"] = ["label" => "Status"];
		$fields["profession"] = ["label" => "Profissão"];
		$fields["email"] = ["label" => "Email"];
		$fields["primary_phone"] = ["label" => "Telefone", "handler" => function ($row) {
			return $row->primary_phone_number;
		}];
		$fields["primary_phone_clean"] = ["label" => "Telefone Limpo", "handler" => function ($row) {
			return preg_replace("/[^0-9]/", "", $row->primary_phone_number);
		}];
		$fields["secondary_phone"] =  ["label" => "Tel. Secundário", "handler" => function ($row) {
			return $row->secondary_phone_number;
		}];
		$fields["secondary_phone_clean"] =  ["label" => "Tel. Secundário Limpo", "handler" => function ($row) {
			return  preg_replace("/[^0-9]/", "", $row->secondary_phone_number);
		}];
		$fields["interest"] = ["label" => "Interesse"];
		$fields["data"] = ["label" => "Data", "handler" => function ($row) {
			return formatDate($row->created_at);
		}];
		return $fields;
	}

	public function filters()
	{
		$filters = [];
		$filters[] = new FilterByPresetDate(["label" => "Data de Criação"]);
		$filters[] = new FilterByText([
			"column" => "data->name",
			"label" => "Nome",
			"index" => "name"
		]);
		$filters[] = new FilterByText([
			"column" => "data->email",
			"label" => "Email",
			"index" => "email"
		]);
		$filters[] = new LeadsByPhone();
		$filters[] = new LeadsByStatus();
		$filters[] = new FilterByTags(Lead::class);
		$filters[] = new FilterByText([
			"column" => "data->source",
			"label" => "Origem",
			"index" => "source"
		]);
		return $filters;
	}

	public function fields()
	{
		$cards = [];
		$fields = [];
		if (request("page_type") == "edit") {
			$fields[] = new BelongsTo([
				"label" => "Status",
				"required" => true,
				"field" => "status_id",
				"options" => Status::select("id as id", "name as value")->get()
			]);
		}
		$fields[] = new Text([
			"label" => "Nome Completo",
			"field" => "name",
			"rules" => ["required", "max:255"]
		]);
		$fields[] = 	new Text([
			"type" => "date",
			"label" => "Data de Nascimento",
			"field" => "birthdate",
			"rules" => ["max:255"],
		]);
		$fields[] = new Text([
			"label" => "Profissão",
			"field" => "profession",
			"rules" => ["max:255"]
		]);
		$fields[] = new TextArea([
			"label" => "Interesse",
			"field" => "interest",
			"rows" => 10,
			"rules" => ["max:255"]
		]);
		$cards[] = new Card("Informações Básicas", $fields);

		$cards[] = new Card("Contato", [
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
		]);

		$cards[] = new Card("Localidade", [
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
		]);

		$cards[] = new Card("Extras", [
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
		]);

		return $cards;
	}

	public function useTags()
	{
		return true;
	}

	public function tableAfterRow($row)
	{
		return view("admin.leads.after_row", compact("row"))->render();
	}
}
