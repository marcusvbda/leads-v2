<?php

use App\Http\Controllers\WebhookController;
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
		$leads = Lead::orderBy("id", "desc")->get();
		$controller = new WebhookController();
		foreach ($leads as $lead) {
			$this->reprocessSources($controller, $lead);
		}
	}

	private function reprocessSources($controller, $lead)
	{
		$content = @$lead->data->lead_api;
		$sources = ["RD Station"];
		if ($content) {
			$sources = $controller->getSources($content, ["RD Station"]);
		}
		$_data = $lead->data;
		$_data->source = $sources;
		$lead->data = $_data;
		$lead->save();
	}
}
