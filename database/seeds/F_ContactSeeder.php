<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Models\{
	ContactType,
	EmailTemplate,
	LeadAnswer,
	Objection
};

class F_ContactSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createContactType();
		$this->createObjection();
		$this->createAnswer();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
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
