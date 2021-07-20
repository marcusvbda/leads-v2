<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use App\Http\Filters\Users\UsersByTenant;
use ResourcesHelpers;

class Usuarios extends Resource
{
	public $model = \App\User::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Usuários";
	}

	public function singularLabel()
	{
		return "Usuário";
	}

	public function icon()
	{
		return "el-icon-user";
	}

	public function search()
	{
		return ["name", "email"];
	}

	public function storeButtonlabel()
	{
		return "<span class='el-icon-s-promotion mr-2'></span>Enviar convite para novo usuário";
	}

	public function table()
	{
		$user = Auth::user();
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["name"] = ["label" => "Nome"];
		$columns["email"] = ["label" => "E-mail"];
		if ($user->hasRole(["super-admin"])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
		$columns["role_name"] = ["label" => "Grupo de Acesso", "sortable" => false];
		return $columns;
	}

	public function canCreate()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canUpdate()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canViewList()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canDelete()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}

	public function canView()
	{
		return false;
	}

	public function filters()
	{
		$user = Auth::user();
		$filters = [];
		if ($user->hasRole(["super-admin"])) $filters[] = new UsersByTenant();
		return $filters;
	}

	public function beforeListSlot()
	{
		$resource = ResourcesHelpers::find("convites");
		$data = $resource->model;
		if (Auth::user()->hasRole(["super-admin"])) {
			if (@$_GET["tenant_id"])
				$data = $data->whereTenantId($_GET["tenant_id"]);
		}
		$data = $data->paginate($this->getPerPage($resource));
		if ($data->count() <= 0) return;
		else $view =  view("vStack::resources.partials._table", compact("resource", "data"))->render();
		return "
	    <div class='my-5'>
	        <h4 class='mb-4'><span class='el-icon-s-promotion mr-2 mr-2'></span> Convites Pendentes</h4>
	        $view
	    </div>
	    ";
	}

	protected function getPerPage($resource)
	{
		$results_per_page = $resource->resultsPerPage();
		$per_page = is_array($results_per_page) ? ((in_array(@$_GET['per_page'] ? $_GET['per_page'] : [], $results_per_page)) ? $_GET['per_page'] : $results_per_page[0]) : $results_per_page;
		return $per_page;
	}
}