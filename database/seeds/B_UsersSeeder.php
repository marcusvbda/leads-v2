<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Models\{
	Tenant,
	Polo,
	Department
};

class B_UsersSeeder extends Seeder
{
	private $tenant = null;
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createTenant();
		$this->createPolos();
		$this->createDepartments();
		$this->createUsers();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createDepartments()
	{
		DB::table("departments")->truncate();
		Department::create([
			"name" => "Vendas",
			"tenant_id" => $this->tenant->id
		]);
		Department::create([
			"name" => "Marketing",
			"tenant_id" => $this->tenant->id
		]);
		Department::create([
			"name" => "Suporte",
			"tenant_id" => $this->tenant->id
		]);
		Department::create([
			"name" => "Financeiro",
			"tenant_id" => $this->tenant->id
		]);
	}

	private function createTenant()
	{
		DB::table("tenants")->truncate();
		$this->tenant = Tenant::create([
			"name" => "Universidade de Marilia",
			"data" => [
				"city" => "Marilia",
				"state" => "São Paulo"
			]
		]);
	}

	private function createPolos()
	{
		DB::table("polos")->truncate();
		foreach (DB::connection("old_mysql")->table("_tenants")->get() as $old_tenant) {
			$this->tenant->polos()->create([
				"name" => $old_tenant->nome,
				"data" => [
					"head" => $old_tenant->nome == "Sede Marilia - SP" ? true : false,
					"city" => $old_tenant->cidade,
					"surrounding" => $old_tenant->arredores,
					"old_tenant_id" => $old_tenant->id
				],
				"created_at" => $old_tenant->created_at
			]);
		}
	}

	private function createUsers()
	{
		DB::table("users")->truncate();
		foreach (DB::connection("old_root_mysql")->table("_usuarios")->get() as $old_root_user) {
			$client_user = DB::connection("old_mysql")->table("_usuarios")->where("id", $old_root_user->client_usuario_id)->first();
			if (@$client_user->id) {
				$is_root = false;
				$user = new User();
				$user->name = $client_user->nome;
				$user->email = $old_root_user->email;
				$user->password = "123mudar321";
				if ($user->email == "root.unimar@root.com") {
					$user->password = "roottoor";
					$is_root = true;
				}
				$user->tenant_id = $this->tenant->id;
				$user->save();
				$old_polo_ids = DB::connection("old_mysql")->table("_tenants_usuarios")->where("usuario_id", $client_user->id)->pluck("tenant_id")->toArray();
				if ($is_root) {
					$polo_ids = Polo::pluck("id")->toArray();
				} else {
					$polo_ids = Polo::whereIn("data->old_tenant_id", $old_polo_ids)->pluck("id")->toArray();
				}
				$user->polos()->sync($polo_ids);
				if ($client_user->protegido || 	$is_root) {
					$user->assignRole("super-admin");
				} else {
					if ($client_user->root) {
						$user->assignRole("admin");
					} else {
						$user->assignRole("operador");
					}
				}
			}
		}
	}
}
