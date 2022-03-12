<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\{OrderByScope};
use marcusvbda\vstack\Vstack;

class WppSession extends DefaultModel
{
	protected $table = "wpp_sessions";

	public $casts = [
		"data" => "object",
		"enabled" => "boolean",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function getTokenAttribute()
	{
		return @$this->data->token ?? "";
	}

	public function getStatusCheckAttribute()
	{
		$token = $this->token;
		return "<wpp-status-check session='$token'></wpp-status-check>";
	}

	public function getLabelAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->name, $this->token);
	}
}
