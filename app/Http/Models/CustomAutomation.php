<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use Arr;
use marcusvbda\vstack\Services\SendMail;

class CustomAutomation extends DefaultModel
{
	protected $table = "custom_automations";
	public $casts = [
		"data" => "json"
	];

	const _TRIGGERS_ = [
		["value" => "store", "label" => "Novo Lead"],
		["value" => "conversion", "label" => "Conversão de Lead"],
		["value" => "schedule", "label" => "Agendamento"],
		["value" => "five_days", "label" => "A cada 5 dias"],
		["value" => "fifteen_days", "label" => "A cada 15 dias"],
		["value" => "third_days", "label" => "A cada 30 dias"],
	];

	// setters
	public function setTriggerAttribute($value)
	{
		setModelDataValue($this, "trigger", $value);
	}

	public function setStatusIdAttribute($value)
	{
		setModelDataValue($this, "status_id", $value);
	}

	public function setEmailTemplateIdAttribute($value)
	{
		setModelDataValue($this, "email_template_id", $value);
	}
	// setters


	// getters
	public function getEmailTemplateIdAttribute()
	{
		return Arr::get($this->data, "email_template_id");
	}

	public function getTriggerAttribute()
	{
		return Arr::get($this->data, "trigger");
	}

	public function getStatusIdAttribute()
	{
		return Arr::get($this->data, "status_id");
	}

	public function getFTriggerAttribute()
	{
		return $this->getValueFromConst($this->trigger, static::_TRIGGERS_);
	}

	public function getEmailTemplateAttribute()
	{
		return Email::find($this->email_template_id);
	}

	public function getFEmailTemplateAttribute()
	{
		return $this->email_template->name;
	}

	public function getStatusAttribute()
	{
		return Status::find($this->status_id);
	}

	public function getStatusNameAttribute()
	{
		return $this->status->name;
	}
	// getters

	private function getValueFromConst($index, $options)
	{
		$found = array_filter($options, function ($x) use ($index) {
			if (Arr::get($x, "value") == $index) return $x;
		});
		return Arr::get(current($found), "label");
	}


	public function execute($lead)
	{
		$lead_id = $lead->id;
		$automation_id = $this->id;
		dispatch(function () use ($lead_id, $automation_id) {
			$lead = Lead::findOrFail($lead_id);
			$automation = CustomAutomation::findOrFail($automation_id);
			$email_template = $automation->email_template;
			$body = Arr::get($email_template->body, "body");
			$subject = $email_template->subject;
			SendMail::to($lead->email, $subject, $body);
			$email_content = [
				"subject" => $email_template->subject,
				"body" => $email_template->body,
			];
			$sent = new \App\Http\Models\AutomationSentEmail;
			$sent->custom_automation_id = $automation->id;
			$sent->email_content = $email_content;
			$sent->lead_id = $lead_id;
			$sent->save();
		})->onQueue("automation-queued-email");
	}
}