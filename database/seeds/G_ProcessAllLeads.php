<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
	Lead,
};

class G_ProcessAllLeads extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->handleProcess();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function handleProcess()
	{
		$leads = Lead::get();
		foreach ($leads as $lead) {
			dd($lead);
		}
	}
}
