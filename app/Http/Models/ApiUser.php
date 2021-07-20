<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;

class ApiUser extends DefaultModel
{
	protected $table = "api_users";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [""];

	public $casts = [
		"data" => "object",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}
}