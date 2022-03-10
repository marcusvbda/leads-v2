<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\{OrderByScope};

class WppMessage extends DefaultModel
{
	protected $table = "wpp_messages";

	public $casts = [
		"data" => "object",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}
}
