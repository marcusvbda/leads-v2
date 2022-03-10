<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\{OrderByScope};

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
		return $this->data->token ?? [];
	}

	public function getStringTokenAttribute()
	{
		return json_encode($this->token) ?? "";
	}

	public function getStatusCheckAttribute()
	{
		$string_token = $this->string_token;
		$code = $this->code;
		return "<wpp-status-check code='$code' :session='$string_token'></wpp-status-check>";
	}
}
