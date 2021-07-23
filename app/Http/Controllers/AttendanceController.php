<?php

namespace App\Http\Controllers;

use App\Http\Models\ContactType;
use App\Http\Models\CustomAutomation;
use App\Http\Models\Lead;
use App\Http\Models\LeadAnswer;
use App\Http\Models\Objection;
use App\Http\Models\Status;
use Illuminate\Http\Request;
use Auth;
use ResourcesHelpers;
use Carbon\Carbon;
use marcusvbda\vstack\Services\Messages;

class AttendanceController extends Controller
{
	public function index()
	{
		if (!hasPermissionTo('edit-leads')) abort(403);
		$logged_user = Auth::user()->load("department");
		return view("admin.leads.attendance", compact("logged_user"));
	}

	public function transferDepartment($code, Request $request)
	{
		$resource = ResourcesHelpers::find("leads");
		if (!$resource->canUpdate()) abort(403);
		$lead = Lead::findByCodeOrFail($code);
		$lead->department_id = $request["department_id"];
		$lead->save();
		return ["sucess" => true];
	}

	public function finish($code)
	{
		$resource = ResourcesHelpers::find("leads");
		if (!$resource->canUpdate()) abort(403);
		$lead = Lead::findByCodeOrFail($code);

		$lead->status_id =  Status::value("finished")->id;
		$lead->finished_at = Carbon::now();
		$lead->responsible_id = Auth::user()->id;
		$lead->save();
		Messages::send("success", "Contato Salvo");
		$this->sendAutomationEmail($lead, "finish");
		return ["success" => true];
	}


	public function registerContact($code, Request $request)
	{
		$resource = ResourcesHelpers::find("leads");
		if (!$resource->canUpdate()) abort(403);
		$lead = Lead::findByCodeOrFail($code);
		$now =  Carbon::now();
		$user = Auth::user();
		$answer = LeadAnswer::findOrFail($request["answer_id"]);
		$type = ContactType::findOrfail($request["type_id"]);
		$objection = Objection::find($request["objection_id"]);
		$new_status = $this->getNewStatus($answer);
		$this->sendAutomationEmail($lead, "schedule");

		$lead = $this->logConversions($lead, $now, $user, $new_status);
		$lead = $this->logTries($lead, $now, $user, $type, @$objection->description, @$request["other_objection"], @$request['obs']);

		$lead->status_id = $new_status->id;

		if ($answer->need_objection) {
			$lead->objection = $objection->description;
			$lead->other_objection = @$request["other_objection"];
		} else {
			$lead->objection = null;
			$lead->other_objection = null;
		}

		if ($answer->need_schedule) {
			$lead->schedule = $request["schedule"];
		} else {
			$lead->schedule = null;
		}

		$lead->responsible_id = $user->id;
		$lead->save();
		Messages::send("success", "Contato Salvo");
		$this->sendAutomationEmail($lead, "conversion");
		return ["success" => true];
	}

	private function getNewStatus($answer)
	{
		if ($answer->need_objection && $answer->is_neutral) return  Status::value("neutral_with_objection");
		if ($answer->need_schedule && $answer->is_neutral) return  Status::value("neutral");

		if ($answer->need_objection && $answer->is_positive) return  Status::value("interest_with_objection");
		if ($answer->need_schedule && $answer->is_positive) return  Status::value("interest");

		if ($answer->need_objection && $answer->is_negative) return  Status::value("canceled");
		if ($answer->need_schedule && $answer->is_negative) return  Status::value("schedule");

		if ($answer->need_test) return  Status::value("schedule_test");

		return  Status::value("waiting");
	}

	private function sendAutomationEmail($lead, $trigger)
	{
		if ($lead->email) {
			foreach (CustomAutomation::where("data->trigger", $trigger)->where("data->status_id", $lead->status->id)->get() as $automation) {
				$automation->execute($lead);
			}
		}
	}

	private function logTries($lead, $now, $user, $type, $objection, $other_objection, $obs)
	{
		$tries = $lead->tries;
		array_unshift($tries, [
			"type" => $type->description,
			"date" => $now->format("d/m/Y"),
			"timestamp" => $now->format("H:i:s"),
			"objection" => @$objection,
			"other_objection" => @$other_objection,
			"obs" => @$obs,
			"user" => $user->name,
			"comment" => null
		]);
		$lead->tries = $tries;
		return $lead;
	}

	private function logConversions($lead, $now, $user, $new_status)
	{
		$conversions = $lead->conversions;
		array_unshift($conversions, [
			"obs" => @$request["obs"],
			"date" =>  $now->format("d/m/Y"),
			"desc" => "Converteu no funil de produção de <b>" . $lead->status->name . "</b> para <b>" . $new_status->name . "</b>",
			"user" => $user->name,
			"timestamp" => $now->format("H:i:s")
		]);
		$lead->conversions = $conversions;
		return $lead;
	}
}