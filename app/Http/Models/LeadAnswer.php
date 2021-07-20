<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use Arr;


class LeadAnswer extends DefaultModel
{
	protected $table = "lead_answers";
	public $appends = ["f_type"];
	const _TYPES_ = [
		["value" => "neutral", "label" => "Resposta Neutra"],
		["value" => "negative", "label" => "Resposta Negativa"],
		["value" => "positive", "label" => "Resposta Positiva"]
	];

	const _BEHAVIORS_ = [
		["value" => "need_objection", "label" => "Solicitar Objeção"],
		["value" => "need_schedule", "label" => "Reagendar Contato"],
		["value" => "need_schedule_test", "label" => "Agendar Vestibular"]
	];

	public function getNeedTestAttribute()
	{
		return $this->behavior === 'need_schedule_test';
	}

	public function getNeedScheduleAttribute()
	{
		return in_array($this->behavior, ['need_schedule', 'need_schedule_test']);
	}

	public function getNeedObjectionAttribute()
	{
		return $this->behavior === 'need_objection';
	}

	public function getIsNeutralAttribute()
	{
		return $this->type === 'neutral';
	}

	public function getIsNegativeAttribute()
	{
		return $this->type === 'negative';
	}

	public function getIsPositiveAttribute()
	{
		return $this->type === 'positive';
	}

	public function getFTypeAttribute()
	{
		return $this->getValueFromConst($this->type, static::_TYPES_);
	}

	public function getFBehaviorAttribute()
	{
		return $this->getValueFromConst($this->behavior, static::_BEHAVIORS_);
	}

	private function getValueFromConst($index, $options)
	{
		$found = array_filter($options, function ($x) use ($index) {
			if (Arr::get($x, "value") == $index) return $x;
		});
		return Arr::get(current($found), "label");
	}
}