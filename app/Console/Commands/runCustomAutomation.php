<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Models\{CustomAutomation, Lead};

use Carbon\Carbon;

class runCustomAutomation extends Command
{
	protected $signature = 'command:run-custom-automations';
	protected $description = 'Run Custom Automation';

	public function __construct()
	{
		parent::__construct();
	}

	public function handle()
	{
		foreach (CustomAutomation::whereNotIn("data->trigger", ["store", "conversion", "schedule"])->get() as $automation) {
			$this->executeAutomation($automation);
		}
	}

	private function executeAutomation($automation)
	{
		$trigger = $automation->trigger;
		$status = $automation->status;
		$leads = $this->getLeads($automation, $status, $trigger);
		foreach ($leads as $lead) {
			$automation->execute($lead);
		}
	}

	private function getNextDate($qty_days)
	{
		return Carbon::now()->addDays($qty_days);
	}

	private function getLeads($automation, $status, $trigger)
	{
		$sub_status_id = $status->sub_status->pluck("id");
		return Lead::whereIn("lead_substatus_id", $sub_status_id)
			->where("updated_at", ">=", $automation->created_at)
			->where("data->email", "!=", "null")
			->whereDoesntHave("automation_sent_emails", function ($q) use ($trigger) {
				$qty_days = [
					"five_days" => 5,
					"fifteen_days" => 15,
					"third_days" => 30,
				];
				$q->whereHas("custom_automation", function ($q)  use ($trigger) {
					$q->where("data->trigger", $trigger);
				})->whereDate("created_at", ">", $this->getNextDate($qty_days[$trigger]));
			})->where("created_at", ">=", $automation->created_at)->get();
	}
}