<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;

class Module extends DefaultModel
{
	protected $table = "modules";

	public $casts = [
		"new_badge" => "boolean",
		"polo_ids" => "array"
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function getFNewBadgeAttribute()
	{
		return getEnabledIcon(@$this->new_badge);
	}
}
