<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use marcusvbda\vstack\Vstack;

class LandingPage extends DefaultModel
{
	protected $table = "landing_pages";

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
		if ($this->published) {
			return "<span class='badge badge-success'>Publicada</span>";
		} else {
			return "<span class='badge badge-default'>Rascunho</span>";
		}
	}

	public function getLabelAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->name, $this->published_badge);
	}

	public function getstatsBtnAttribute()
	{
		$code = $this->code;
		$published = json_encode($this->published);
		return "<publish-or-stats code='$code' :published='$published'></publish-or-stats>";
	}

	public function setActionUrlAttribute($value)
	{
		setModelDataValue($this, "action_url", $value);
	}

	public function getActionUrlAttribute()
	{
		return $this->data->action_url ?? null;
	}

	public function setDownloadUrlAttribute($value)
	{
		setModelDataValue($this, "download_url", $value);
	}

	public function getDownloadUrlAttribute()
	{
		return $this->data->download_url ?? null;
	}
}
