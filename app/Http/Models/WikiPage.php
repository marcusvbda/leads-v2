<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;

class WikiPage extends DefaultModel
{
	protected $table = "wiki_pages";
	// public $cascadeDeletes = [];
	public $restrictDeletes = ["users"];

	public $appends = ["code"];

	public $casts = [
		"data" => "object",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public static function hasTenant()
	{
		return false;
	}
}
