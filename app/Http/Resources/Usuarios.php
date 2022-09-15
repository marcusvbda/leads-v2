<?php


namespace App\Http\Resources;

use App\Http\Controllers\Auth\UsersController;
use marcusvbda\vstack\Resource;
use Auth;
use App\Http\Filters\Users\UsersByTenant;
use App\Http\Models\Department;
use App\Http\Models\Role;
use App\Http\Models\Tenant;
use App\Http\Models\UserInvite;
use marcusvbda\vstack\Fields\BelongsTo;
use marcusvbda\vstack\Fields\Card;
use marcusvbda\vstack\Fields\Text;
use ResourcesHelpers;
use App\User;
use DB;
use marcusvbda\vstack\Filters\FilterByOption;

class Usuarios extends Resource
{
	public $model = User::class;


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
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["name"] = ["label" => "Nome"];
		$columns["email"] = ["label" => "E-mail"];
		$columns["qty_polos"] = ["label" => "Polos Vinculados", "sortable" => false, "handler" => function ($row) {
			return $row->polos()->count();
		}];
		$columns["role_name"] = ["label" => "Grupo de Acesso", "sortable" => false];
		$columns["department->name"] = ["label" => "Departamento", "sortable" => false];
		return $columns;
	}

	public function canClone()
	{
		return false;
	}

	public function canCreate()
	{
		return hasPermissionTo("create-users");
	}

	public function canUpdate()
	{
		return true;
	}

	public function canUpdateRow($row)
	{
		return hasPermissionTo("edit-users") || $row->id == Auth::user()->id;
	}

	public function canViewList()
	{
		return hasPermissionTo("viewlist-users");
	}

	public function canDelete()
	{
		return hasPermissionTo("destroy-users");
	}

	public function canCustomizeMetrics()
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

	public function canView()
	{
		return false;
	}

	public function filters()
	{
		$user = Auth::user();
		$filters = [];
		if ($user->hasRole(["super-admin"])) {
			$filters[] = new FilterByOption([
				"label" => "Tenant",
				"field" => "tenant_id",
				"model" => Tenant::class
			]);
			$filters[] = new FilterByOption([
				"label" => "Departamento",
				"field" => "department_id",
				"model" => Department::class
			]);
		}
		return $filters;
	}

	public function afterListSlot()
	{
		$resource = ResourcesHelpers::find("convites");
		$data = $resource->model;
		if (Auth::user()->hasRole(["super-admin"])) {
			if (@$_GET["tenant_id"]) {
				$data = $data->whereTenantId($_GET["tenant_id"]);
			}
		}
		$data = $data->paginate($this->getPerPage($resource));
		if ($data->count() <= 0) {
			return;
		} else {
			$view =  view("vStack::resources.partials._table", compact("resource", "data"))->render();
		}
		return "
        <div class='my-5'>
            <h4 class='mb-4'><span class='el-icon-s-promotion mr-2'></span> Convites Pendentes</h4>
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

	public function secondCrudBtn()
	{
		if (!request("content") && !request("id")) {
			return false;
		}
		return parent::secondCrudBtn();
	}

	public function firstCrudBtn()
	{
		if (!request("content") && !request("id")) {
			return [
				"size" => "small",
				"field" => "invite",
				"type" => "success",
				"content" => "<div class='d-flex flex-row'>
							<i class='far fa-paper-plane mr-2'></i>
							Convidar
						</div>"
			];
		}
		return parent::firstCrudBtn();
	}

	private function inviteFields()
	{
		$user = Auth::user();
		$cards = [];
		$fields = [
			new Text([
				"label" => "Email",
				"description" => "Email para qual o convite será enviado",
				"field" => "email",
				'rules' => ['required', 'email', function ($attribute, $value, $fail) {
					if (User::whereEmail($value)->count() > 0) $fail("Este E-mail já está utilizado por outro usuário !!");
				}],
			]),
			new BelongsTo([
				"label" => "Grupo de Acesso",
				"field" => "role_id",
				"required" => true,
				"options" => $this->getRoleOptions()
			]),
			new BelongsTo([
				"label" => "Polos",
				"field" => "polo_ids",
				"description" => "Polos os quais este usuário faz parte",
				"multiple" => true,
				"options" => $user->tenant->polos()->select("id as id", "name as value")->get()
			]),
			new BelongsTo([
				"label" => "Departamento",
				"field" => "department_id",
				"required" => false,
				"model" => Department::class
			]),
		];
		$cards[] = new Card("Informações", $fields);
		return $cards;
	}

	private function getRoleOptions()
	{
		$roles = config("roles", []);
		$is_super_admin = Auth::user()->hasRole(["super-admin"]);
		$roles = array_filter(array_map(function ($key) use ($roles, $is_super_admin) {
			$found_role = data_get($roles, $key);
			if (!data_get($found_role, "hidden") || $is_super_admin) {
				return ["id" => $key, "value" => data_get($found_role, "title", $key)];
			}
		}, array_keys($roles)));
		return $roles;
	}

	private function editFields($content)
	{
		$user = Auth::user();
		$cards = [];

		$fields = [
			new Text([
				"label" => "Email",
				"field" => "email",
				"required" => true,
				"disabled" => true,
			]),
			new Text([
				"label" => "Nome",
				"field" => "name",
				"required" => true,
			]),
			new BelongsTo([
				"label" => "Polos",
				"field" => "polo_ids",
				"description" => "Polos os quais este usuário faz parte",
				"multiple" => true,
				"default" => array_map(function ($row) {
					return strval($row);
				}, $content ? $content->polos()->pluck("id")->toArray() : []),
				"options" => $user->tenant->polos()->select("id as id", "name as value")->get(),
				"all_options_label" => "Todos os Polos"
			]),
			new BelongsTo([
				"label" => "Departamento",
				"field" => "department_id",
				"required" => false,
				"model" => Department::class,
			]),
		];
		$block_edit_role = @request("content") && @request("content")->id == @$user->id;
		$fields[] = new BelongsTo([
			"label" => "Grupo de Acesso",
			"field" => "role",
			"required" => true,
			"default" => @$user->role,
			"disabled" => $block_edit_role,
			"options" => $this->getRoleOptions()
		]);
		$cards[] = new Card("Informações", $fields);
		return $cards;
	}

	public function fields()
	{
		if (!request("content") && !request("id")) {
			return $this->inviteFields();
		}
		return $this->editFields(request("content"));
	}

	public function storeMethod($id, $data)
	{
		if (!$id) {
			$invite = UserInvite::create([
				"email" => request("email"),
				"data" => request()->except(["email", "clicked_btn"]),
				"tenant_id" => Auth::user()->tenant_id,
			]);
			(new UsersController)->inviteEmail($invite);
			$route = route('resource.index', ["resource" => $this->id]);
			return ["success" => true, "route" => $route];
		}
		$user = User::findOrFail($id);
		$user->fill(request()->except(["role_id", "polo_ids"]));
		$user->save();
		if (@$data["data"]["role_id"]) {
			$user->syncRoles([Role::findOrFail($data["data"]["role_id"])]);
		}
		$user->polos()->sync(@$data["data"]["polo_ids"] ? $data["data"]["polo_ids"] : []);
		if (request("clicked_btn") == "save") {
			$route = route('resource.edit', ["resource" => $this->id, "code" => $user->code]);
		} else {
			$route = route('resource.index', ["resource" => $this->id]);
		}
		return ["success" => true, "route" => $route];
	}
}
