<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use marcusvbda\vstack\Vstack;

class LandingPage extends DefaultModel
{
	protected $table = "landing_pages";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [];

	public $appends = ["code", "url"];

	public $casts = [
		"data" => "object",
		"published" => "boolean",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public static function sluggable()
	{
		return [
			"source" => "name",
			"raw" => "slug",
			"destination" => "path"
		];
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getUrlAttribute()
	{
		return "/landing-pages/" . $this->tenant->code . "/" . $this->path;
	}

	public function getFUrlAttribute()
	{
		return "<a href='{$this->url}'>{$this->url}</a>";
	}

	public function getPublishedBadgeAttribute()
	{
		$published_icon = getEnabledIcon($this->published);
		return $published_icon . ' ' . ($this->published ? "Publicado" : "Não Publicado");
	}

	public function getLabelAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->name, $this->published_badge);
	}
}
