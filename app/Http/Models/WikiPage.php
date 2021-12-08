<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;

class WikiPage extends DefaultModel
{
	protected $table = "wiki_pages";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [];

	public $appends = ["code", "url"];

	public $casts = [
		"data" => "object",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public static function sluggable()
	{
		return [
			"source" => "title",
			"raw" => "slug",
			"destination" => "path"
		];
	}

	public static function hasTenant()
	{
		return false;
	}

	public function getUrlAttribute()
	{
		return "/admin/wiki-page/" . $this->path;
	}

	public function getFUrlAttribute()
	{
		return "<a href='{$this->url}'>{$this->url}</a>";
	}
}
