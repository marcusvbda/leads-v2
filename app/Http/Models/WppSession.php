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

	public function getStringTokenAttribute()
	{
		return json_encode($this->data->token) ?? "";
	}
}
