<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Models\{
	Status,
};

class C_StatusesSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createStatuses();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function createStatuses()
	{
		Status::truncate();
		$counter = 0;
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
}
