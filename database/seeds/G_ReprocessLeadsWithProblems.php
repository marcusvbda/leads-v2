<?php

use App\Http\Controllers\WebhookController;
use Illuminate\Database\Seeder;
use App\Http\Models\{
	Lead,
	Webhook,
};
use Illuminate\Http\Request;

class G_ReprocessLeadsWithProblems extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$controller = new WebhookController();
		$webhook = Webhook::first();
		foreach ($this->getLeadsWithProblems() as $lead) {
			$content = @$lead->webhook_request->content;
			if ($content) {
				$controller->handler($webhook->token, new Request((array) ["lead_api" => $content]), $lead->id);
			}
		}
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function getLeadsWithProblems()
	{
		$leads = Lead::whereRaw("JSON_UNQUOTE(JSON_EXTRACT(DATA,'$.name')) = 'null'")->get();
		return $leads;
	}
}
