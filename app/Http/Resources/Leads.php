<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use App\Http\Models\Lead;
use App\Http\Actions\Leads\{
	LeadResponsibleChange,
};
use App\Http\Models\Department;
use App\User;
use marcusvbda\vstack\Fields\{
	BelongsTo,
	Card,
	DateTime,
	Text,
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
	];

	public function label()
	{
		return "Leads";
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
		return ["name", "email", "doc_number"];
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id", "size" => "100px"];
		$columns["label"] = ["label" => "Nome", "sortable_index" => "name"];
		$columns["f_updated_at_badge"] = ["label" => "Data", "sortable_index" => "created_at", "size" => "200px"];
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
			$actions[] = new LeadResponsibleChange();
		}
		if (hasPermissionTo("destroy-leads")) {
			$actions[] = new MultipleDelete([
				"model" => Lead::class,
				"title" => "Excluir Leads",
				"message" => "Essa ação irá excluir os leads selecionados",
				"success_message" => 'Leads excluídos com sucesso',
			]);
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
		$fields = [
			["label" => "Código", "handler" => fn ($item) => $item->code],
			["label" => "Name", "handler" => fn ($item) => $item->name],
			["label" => "Profissão", "handler" => fn ($item) => $item->profession],
			["label" => "Email", "handler" => fn ($item) => $item->email],
		];
		return $fields;
	}

	public function filters()
	{
		$filters = [];

		$filters[] = new FilterByPresetDate([
			"label" => "Data de Criação",
			"field" => "created_at"
		]);
		$filters[] = new FilterByText([
			"column" => "name",
			"label" => "Nome",
			"index" => "name"
		]);
		$filters[] = new FilterByText([
			"column" => "email",
			"label" => "Email",
			"index" => "email"
		]);
		$filters[] = new FilterByTag(Lead::class);
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
			"label" => "Autor",
			"multiple" => true,
			"model" => User::class,
			"column" => "user_id",
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
			$item_value = data_get($fill_data, $key, "");
			$new_model->{$value} = $item_value;
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
		$fields[] = new Text([
			"label" => "Nome Completo",
			"field" => "name",
			"rules" => ["required", "max:255"],
		]);
		$fields[] = new Text([
			"label" => "RG ou CPF",
			"field" => "doc_number",
			"rules" => ["max:255"],
		]);
		$fields[] = 	new DateTime([
			"type" => "date",
			"label" => "Data de Nascimento",
			"field" => "birthdate",
		]);
		$fields[] = new Text([
			"label" => "Profissão",
			"field" => "profession",
			"rules" => ["max:100"]
		]);
		$cards[] = new Card("Informações Básicas", $fields);

		$cards[] = new Card("Contato", [
			new Text([
				"label" => "Email",
				"field" => "email",
				"rules" => ["max:255", "email"]
			]),
			new Text([
				"label" => "Telefone Primário",
				"field" => "phone",
				"mask" => ["(##) ####-####", "(##) #####-####"],
			]),
			new Text([
				"label" => "Telefone Secundário",
				"field" => "secondary_phone",
				"mask" => ["(##) ####-####", "(##) #####-####"],
			]),
		]);

		$cards[] = new Card("Localidade", [
			new BelongsTo([
				"label" => "País",
				"field" => "country",
				"disabled" => true,
				"default" => "BR",
				"options" => [
					[
						"value" => "BR",
						"label" => "Brasil"
					]
				],
				"rules" => ["required", "max:255"]
			]),
			new Text([
				"label" => "Cep",
				"field" => "zipcode",
				"mask" => ["#####-###"],
				"rules" => ["max:255"]
			]),
			new Text([
				"label" => "Cidade",
				"field" => "city",
				"rules" => ["max:150"]
			]),
			new Text([
				"label" => "Bairro",
				"field" => "district",
				"rules" => ["max:100"]
			]),
			new Text([
				"label" => "Número",
				"field" => "number",
				"rules" => ["max:50"]
			]),
			new Text([
				"label" => "Complemento",
				"field" => "complementary",
				"rules" => ["max:255"]
			]),
		]);

		$cards[] = new Card("Extras", [
			new Text([
				"label" => "Observações",
				"type" => "textarea",
				"field" => "obs",
				"rows" => 10,
				"rules" => ["max:500"],
				"show_value_length" => true,
			]),
		]);

		return $cards;
	}

	public function useTags()
	{
		return true;
	}

	// public function tableAfterRow($row)
	// {
	// 	return view("admin.leads.after_row", compact("row"))->render();
	// }

	public function prepareImportData($data)
	{
		return [
			"success" => true,
			"data" => [
				"polo_id" => Auth::user()->polo_id,
			]
		];
	}
}
