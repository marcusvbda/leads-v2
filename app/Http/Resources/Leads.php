<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use App\Http\Models\Lead;
use App\Http\Actions\Leads\{
	LeadStatusChange,
	LeadTransfer,
	SendWppMessage,
	SendEmail,
};
use App\Http\Filters\Leads\LeadsByPhone;
use App\Http\Filters\Leads\LeadsByStatus;
use App\Http\Models\Department;
use App\Http\Models\Objection;
use App\Http\Models\Status;
use App\User;
use marcusvbda\vstack\Fields\{
	BelongsTo,
	Card,
	Text,
	TextArea,
};
use Auth;
use marcusvbda\vstack\Actions\MultipleDelete;
use marcusvbda\vstack\Filters\FilterByOption;
use marcusvbda\vstack\Filters\FilterByPresetDate;
use marcusvbda\vstack\Filters\FilterByTag;
use marcusvbda\vstack\Filters\FilterByText;

class Leads extends Resource
{
	public $model = Lead::class;
	public $importColumnIndexes = [
		"nome" => "name",
		"email" => "email",
		"nascimento" => "birthdate",
		"telefone" => "phone_number",
		"celular" => "cellphone_number",
		"profissão" => "profession",

		"cep" => "zipcode",
		"cidade" => "city",
		"bairro" => "district",
		"número" => "address_number",
		"complemente" => "complementary",
		"interesse" => "interest",
		"comentários" => "comment",
		"observações" => "obs",
	];

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
		return false;
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
		$columns["contact"] = ["label" => "Contato", "sortable_index" => "data->email"];
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
		$actions = [];
		if (hasPermissionTo("edit-leads")) {
			$actions[] = new LeadStatusChange();
			$actions[] = new LeadTransfer();
		}
		if (hasPermissionTo("destroy-leads")) {
			$actions[] = new MultipleDelete([
				"title" => "Excluir Leads",
				"message" => "Essa ação irá excluir os leads selecionados",
				"success_message" => 'Leads excluídos com sucesso',
			]);
		}
		if (getEnabledModuleToUser("whatsapp")) {
			$actions[] = new SendWppMessage();
		}
		if (getEnabledModuleToUser("email-integrator")) {
			$actions[] = new SendEmail();
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
		return hasPermissionTo("viewlist-leads");
	}

	public function canImport()
	{
		return hasPermissionTo("create-leads");
	}


	public function canExport()
	{
		return hasPermissionTo("viewlist-leads");
	}

	public function exportColumns()
	{
		$fields[] = ["label" => "Código", "handler" => function ($item) {
			return $item->code;
		}];
		$fields[] = ["label" => "Nome", "handler" => function ($item) {
			return $item->name;
		}];
		$fields[] = ["label" => "Origens", "handler" => function ($row) {
			return @implode(", ", (array)data_get($row, "data.source", []));
		}];
		$fields[] = ["label" => "Status", "handler" => function ($row) {
			return @$row->status->name;
		}];
		$fields[] = ["label" => "Profissão", "handler" => function ($row) {
			return @$row->profession;
		}];
		$fields[] = ["label" => "Email", "handler" => function ($row) {
			return @$row->email;
		}];
		$fields[] = ["label" => "Telefone", "handler" => function ($row) {
			return @$row->primary_phone;
		}];
		$fields[] = ["label" => "Telefone Limpo", "handler" => function ($row) {
			return preg_replace("/[^0-9]/", "", $row->primary_phone_number);
		}];
		$fields[] =  ["label" => "Tel. Secundário", "handler" => function ($row) {
			return $row->secondary_phone_number;
		}];
		$fields[] =  ["label" => "Tel. Secundário Limpo", "handler" => function ($row) {
			return  preg_replace("/[^0-9]/", "", $row->secondary_phone_number);
		}];
		$fields[] = ["label" => "Interesse", "handler" => function ($row) {
			return @$row->interest;
		}];
		$fields[] = ["label" => "Data", "handler" => function ($row) {
			return formatDate($row->created_at);
		}];
		return $fields;
	}

	public function filters()
	{
		// $user_options = User::selectRaw("id as value,name as label")->get();
		$filters = [];
		// remover
		// $filters[] = new FilterByOption([
		// 	"label" => "Objeções",
		// 	"multiple" => true,
		// 	// "options" => Objection::selectRaw("id as value,description as label")->get(),
		// 	"model" => Objection::class,
		// 	"model_fields" =>  ["value" => "id", "label" => "description"],
		// 	"field" => "objection",
		// 	"handle" =>  function ($query, $value) {
		// 		$objection = Objection::find($value);
		// 		return $query->where("data->objection", $objection?->description);
		// 	}
		// ]);

		// $filters[] = new FilterByOption([
		// 	"label" => "Departamentos",
		// 	"multiple" => true,
		// 	"options" => Department::selectRaw("id as value,name as label")->get(),
		// 	"column" => "department_id",
		// ]);

		// remover

		$filters[] = new FilterByPresetDate([
			"label" => "Data de Criação",
			"field" => "leads.created_at"
		]);
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
		$filters[] = new FilterByOption([
			"label" => "Status",
			"field" => "status_id",
			"model" => Status::class
		]);
		$filters[] = new FilterByTag(Lead::class);
		$filters[] = new FilterByText([
			"column" => "data->source",
			"label" => "Origem",
			"index" => "source"
		]);
		$filters[] = new FilterByOption([
			"label" => "Departamentos",
			"multiple" => true,
			"model" => Department::class,
			"column" => "department_id",
		]);
		$filters[] = new FilterByOption([
			"label" => "Reponsáveis",
			"multiple" => true,
			"model" => User::class,
			"column" => "responsible_id",
		]);
		$filters[] = new FilterByOption([
			"label" => "Cadastrado por ...",
			"multiple" => true,
			"model" => User::class,
			"column" => "user_id",
		]);
		$filters[] = new FilterByOption([
			"label" => "Objeções",
			"multiple" => true,
			"model" => Objection::class,
			"model_fields" =>  ["value" => "id", "label" => "description"],
			"field" => "objection",
			"handle" =>  function ($query, $value) {
				$objection = Objection::find($value);
				return $query->where("data->objection", $objection?->description);
			}
		]);
		$filters[] = new FilterByText([
			"column" => "data->other_objection",
			"label" => "Descrição da Objeção",
			"index" => "other_objection"
		]);

		return $filters;
	}

	public function importerColumns()
	{
		return array_keys($this->importColumnIndexes);
	}

	public function importRowMethod($new, $extra_data)
	{
		$fill_data = array_merge($new, $extra_data ? $extra_data : []);
		$new_model = @$new["id"] ? $this->getModelInstance()->findOrFail($new["id"]) : $this->getModelInstance();
		$columns = $this->importColumnIndexes;

		foreach ($columns as $key => $value) {
			$new_model->{$value} = data_get($fill_data, $key, "");
		}

		$new_model->tenant_id = data_get($fill_data, "tenant_id", null);
		$new_model->polo_id = data_get($fill_data, "polo_id", null);
		$new_model->save();
		return $new_model;
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

	public function prepareImportData($data)
	{
		return ["success" => true, "data" => [
			"polo_id" => Auth::user()->polo_id,
		]];
	}
}
