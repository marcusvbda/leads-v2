<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use Str;

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
		// 
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
		static::saving(function ($model) {
			$slug = Str::slug($model->title);
			$model->attributes["slug"] = $slug;
			$count = static::where("slug", $slug)->count();
			if ($count > 0) {
				$slug = $slug . "-" . $count;
			}
			$model->attributes["path"] = $slug;
		});
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
