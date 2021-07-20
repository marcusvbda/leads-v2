<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\{Role, Permission};

class A_AclSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createPermissions();
		$this->createRoles();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createPermissions()
	{
		DB::table("permissions")->truncate();
		Cache::flush('spatie.permission.cache');
		Permission::create(["group" => "Leads", "name" => "viewlist-leads", "description" => "Ver Listagem de Leads"]);
		Permission::create(["group" => "Leads", "name" => "create-leads", "description" => "Cadastrar Leads"]);
		Permission::create(["group" => "Leads", "name" => "edit-leads", "description" => "Editar Leads"]);
		Permission::create(["group" => "Leads", "name" => "destroy-leads", "description" => "Excluir Leads"]);
		Permission::create(["group" => "Leads", "name" => "view-leads-report", "description" => "Ver Relatório de Leads"]);

		Permission::create(["group" => "Respostas de Contatos", "name" => "viewlist-leadanswer", "description" => "Ver Listagem de Respostas de Contatos"]);
		Permission::create(["group" => "Respostas de Contatos", "name" => "create-leadanswer", "description" => "Cadastrar Respostas de Contatos"]);
		Permission::create(["group" => "Respostas de Contatos", "name" => "edit-leadanswer", "description" => "Editar Respostas de Contatos"]);
		Permission::create(["group" => "Respostas de Contatos", "name" => "destroy-leadanswer", "description" => "Excluir Respostas de Contatos"]);

		Permission::create(["group" => "Tipo de Contato", "name" => "viewlist-contacttype", "description" => "Ver Listagem de Tipo de Contatos"]);
		Permission::create(["group" => "Tipo de Contato", "name" => "create-contacttype", "description" => "Cadastrar Tipo de Contatos"]);
		Permission::create(["group" => "Tipo de Contato", "name" => "edit-contacttype", "description" => "Editar Tipo de Contatos"]);
		Permission::create(["group" => "Tipo de Contato", "name" => "destroy-contacttype", "description" => "Excluir Tipo de Contatos"]);

		Permission::create(["group" => "Objeções", "name" => "viewlist-objections", "description" => "Ver Listagem de Objeções"]);
		Permission::create(["group" => "Objeções", "name" => "create-objections", "description" => "Cadastrar Objeções"]);
		Permission::create(["group" => "Objeções", "name" => "edit-objections", "description" => "Editar Objeções"]);
		Permission::create(["group" => "Objeções", "name" => "destroy-objections", "description" => "Excluir Objeções"]);

		Permission::create(["group" => "Marketing", "name" => "viewlist-email", "description" => "Ver Listagem de Emails"]);
		Permission::create(["group" => "Marketing", "name" => "create-email", "description" => "Cadastrar Emails"]);
		Permission::create(["group" => "Marketing", "name" => "edit-email", "description" => "Editar Emails"]);
		Permission::create(["group" => "Marketing", "name" => "destroy-email", "description" => "Excluir Emails"]);

		Permission::create(["group" => "Automações Customizadas", "name" => "viewlist-automation", "description" => "Ver Listagem de Automações Customizadas"]);
		Permission::create(["group" => "Automações Customizadas", "name" => "create-automation", "description" => "Cadastrar Automações Customizadas"]);
		Permission::create(["group" => "Automações Customizadas", "name" => "edit-automation", "description" => "Editar Automações Customizadas"]);
		Permission::create(["group" => "Automações Customizadas", "name" => "destroy-automation", "description" => "Excluir Automações Customizadas"]);
		Permission::create(["group" => "Automações Customizadas", "name" => "report-automation", "description" => "Relatório de Automações Customizadas"]);

		Permission::create(["group" => "Rating", "name" => "config-rating-behavior", "description" => "Alterar Configuração de Regra de Rating"]);
	}

	private function createRoles()
	{
		DB::table("roles")->truncate();
		DB::table("model_has_roles")->truncate();
		DB::table("role_has_permissions")->truncate();
		Cache::flush('spatie.role.cache');
		$role = Role::create(["name" => "super-admin", "description" => "Super Administrador"]);
		$role = Role::create(["name" => "admin", "Description" => "Administrador"]);
		$role = Role::create(["name" => "acl_teste", "Description" => "acl_teste", "tenant_id" => 1]);
		$role->givePermissionTo(Permission::all());
		$role = Role::create(["name" => "operador", "Description" => "Operador", "tenant_id" => 1]);
		$role->givePermissionTo(Permission::where(function ($q) {
			$q->where("name", "like", "%operar%")->orWhere("name", "like", "%viewlist%");
		})->get());
	}
}