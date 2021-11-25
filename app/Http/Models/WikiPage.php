<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use Str;

class WikiPage extends DefaultModel
{
	protected $table = "wiki_pages";
	// public $cascadeDeletes = [];
	public $restrictDeletes = ["users"];

	public $appends = ["code"];

	public $casts = [
		"data" => "object",
		"cover" => "boolean"
	];

	public static function boot()
	{
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
			if ($model->cover) {
				static::where("cover", true)->update(["cover" => false]);
			} else {
				if (static::where("cover", true)->count() == 0) {
					$model->attributes["cover"] = true;
				}
			}
		});
	}

	public static function hasTenant()
	{
		return false;
	}

	public function getUrlAttribute()
	{
		$path = "/admin/wiki/" . $this->path;
		return "<a href='{$path}'>{$path}</a>";
	}

	public function getFCoverAttribute()
	{
		return getEnabledIcon($this->cover);
	}
}
