<?php

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
			"seq"  => ++$counter,
			"value" => "canceled",
			"name" => "Cancelado",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "schedule",
			"name" => "Contato Agendado",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "waiting",
			"name" => "Aguardando",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "neutral",
			"name" => "Neutro",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "neutral_with_objection",
			"name" => "Neutro com Objeção",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "interest",
			"name" => "Interessado",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "interest_with_objection",
			"name" => "Interessado com Objeção",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "objection",
			"name" => "Com Objeçao",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "objection",
			"name" => "Com Objeçao",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "schedule_test",
			"name" => "Vestibular Agendado",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "schedule_done",
			"name" => "Vestibular Realizado",
		]);
		Status::create([
			"seq"  => ++$counter,
			"value" => "finished",
			"name" => "Matriculado",
		]);
	}
}