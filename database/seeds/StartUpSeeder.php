<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Models\{
	Tenant,
	Department,
	Polo
};
use Illuminate\Support\Facades\DB;

class StartUpSeeder extends Seeder
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
		$this->tenant->polos()->create([
			"name" => "Sede Marilia - SP",
			"data" => [
				"city" => "Marilia - SP",
			]
		]);
	}

	private function createUsers()
	{
		DB::table("users")->truncate();
		$user = new User();
		$user->name = "root";
		$user->email = "root@root.com";
		$user->password = "roottoor";
		$user->tenant_id = $this->tenant->id;
		$user->role = "super-admin";
		$user->save();
		$polo_ids = Polo::pluck("id")->toArray();
		$user->polos()->sync($polo_ids);
	}
}
