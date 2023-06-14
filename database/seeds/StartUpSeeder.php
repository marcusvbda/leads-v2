<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Http\Models\{
	ContactType,
	Tenant,
	Department,
	LeadAnswer,
	Objection,
	Polo,
	Status
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
		$this->createContactType();
		$this->createObjection();
		$this->createAnswer();
		$this->createStatuses();
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function createStatuses()
	{
		Status::truncate();
		Status::create([
			"value" => "canceled",
			"name" => "Cancelado",
		]);
		Status::create([
			"value" => "schedule",
			"name" => "Contato Agendado",
		]);
		Status::create([
			"value" => "waiting",
			"name" => "Aguardando",
		]);
		Status::create([
			"value" => "neutral",
			"name" => "Neutro",
		]);
		Status::create([
			"value" => "interest",
			"name" => "Interessado",
		]);
		Status::create([
			"value" => "objection",
			"name" => "Com Objeçao",
		]);
		Status::create([
			"value" => "interest_with_objection",
			"name" => "Interessado com Objeção",
		]);
		Status::create([
			"value" => "schedule_test",
			"name" => "Vestibular Agendado",
		]);
		Status::create([
			"value" => "finished",
			"name" => "Finalizado",
		]);
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
				"head" => true,
				"city" => "Marilia - SP",
				"surrounding" => [],
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

	private function createAnswer()
	{
		LeadAnswer::truncate();
		LeadAnswer::create(["description" => "Dúvida", "type" => "neutral", "tenant_id" => 1, "behavior" => "need_schedule"]);
		LeadAnswer::create(["description" => "Sem Interesse", "type" => "negative", "tenant_id" => 1, "behavior" => "need_objection"]);
		LeadAnswer::create(["description" => "Interessado", "type" => "positive", "tenant_id" => 1, "behavior" => "need_schedule"]);
	}

	private function createObjection()
	{
		Objection::truncate();
		Objection::create(["description" => "Financeiro", "need_description" => false, "tenant_id" => 1]);
		Objection::create(["description" => "Outro", "need_description" => true, "tenant_id" => 1]);
	}

	private function createContactType()
	{
		ContactType::truncate();
		ContactType::create(["description" => "Telefone", "tenant_id" => 1]);
		ContactType::create(["description" => "Whatsapp", "tenant_id" => 1]);
		ContactType::create(["description" => "Email", "tenant_id" => 1]);
	}
}
