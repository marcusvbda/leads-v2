<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check,
};
use App\Http\Models\Role;
use App\Http\Models\Permission;
use marcusvbda\vstack\Services\Messages;
use Cache;

class GruposDeAcesso extends Resource
{
	public $model = \App\Http\Models\Role::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Grupos de Acesso";
	}

	public function singularLabel()
	{
		return "Grupo de Acesso";
	}

	public function icon()
	{
		return "el-icon-lock";
	}

	public function search()
	{
		return ["name", "description"];
	}

	public function table()
	{
		$user = Auth::user();
		$columns = [];
		$columns["code"] = ["label" => "Código", "sortable_index" => "id"];
		$columns["description"] = ["label" => "Descrição"];
		// $columns["name"] = ["label" => "Nome"];
		if ($user->hasRole(["super-admin"])) $columns["tenant->name"] = ["label" => "Tenant", "sortable_index" => "tenant_id"];
		$columns["f_access_level"] = ["label" => "Nível de Acesso", "sortable" => false];
		$columns["f_created_at_for_humans"] = ["label" => "", "sortable_index" => "created_at"];
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

	public function canViewList()
	{
		return Auth::user()->hasRole(["super-admin", "admin"]);
	}

	public function canView()
	{
		return false;
	}

	public function fields()
	{
		$model = new Role;
		$role = @request()->content;
		$fields =  [
			new Card("Identificação", [
				new Text([
					"label" => "Nome",
					"description" => "Nome de exibição do grupo de acesso",
					"field" => "description",
					"rules" => ["required", "max:255", function ($attribute, $value, $fail) use ($model, $role) {
						$name = $model->makeRoleName($value);
						$tenant_code = @Auth::user()->tenant->code;
						$role_name = $tenant_code . "_" . $name;
						if (Role::where("name", $role_name)->where("id", "!=", @$role->id)->count() > 0) $fail('Este Grupo de acesso já existe');
						if (in_array($role_name, Role::$protected_roles)) return $fail("Não é possível cadastrar um grupo com este nome.");
					}]
				])
			])
		];
		foreach (Permission::groupBy("group")->get() as $permission) {
			$group_fields = [];
			foreach (Permission::where("group", $permission->group)->get() as $group_permission) {
				$group_fields[] = new Check([
					"label"   => $group_permission->description,
					"field"   => str_replace("-", "_", $group_permission->name),
					"default" => !@$role ? false : $role->hasPermissionTo($group_permission->name)
				]);
			}
			$fields[] = new Card($permission->group, $group_fields);
		}
		return $fields;
	}


	public function nothingStoredText()
	{
		return "<h4>Nada cadastrado ainda além de seu grupo...<h4>";
	}

	public function nothingStoredSubText()
	{
		return "<span>Clique no botão abaixo para adicionar o primeiro registro, se seu usuário possuir tal permissão ...</span>";
	}

	public function beforeListSlot()
	{
		return '
        <div class="alert alert-warning" role="alert">
            <strong>Atenção ! </strong>Esta é a listagem de <b>Grupos de Acesso</b>, contendo todos os grupos, exceto o administrador.
        </div>';
	}

	public function storeMethod($id, $data)
	{
		$target = @$id ? $this->getModelInstance()->findOrFail($id) : $this->getModelInstance();
		$target->description  = $data["data"]["description"];
		$target->save();
		Messages::send("success", "Registro salvo com sucesso !!");
		if (request("clicked_btn") == "save") {
			$route = route('resource.edit', ["resource" => $this->id, "code" => $target->code]);
		} else {
			$route = route('resource.index', ["resource" => $this->id]);
		}

		$permissions = request()->except(["id", "description", "resource_id", "clicked_btn"]);

		$new_permissions = [];
		foreach ($permissions as $key => $value) {
			if ($value) {
				$new_permissions[] = str_replace("_", "-", $key);
			}
		}

		$target->syncPermissions($new_permissions);
		Messages::send("success", "Grupo de Acesso salvo com sucesso");
		Cache::flush('spatie.permission.cache');
		Cache::flush('spatie.role.cache');
		return ["success" => true, "route" => $route, "model" => $target];
	}
}