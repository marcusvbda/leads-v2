<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use marcusvbda\vstack\Vstack;

class Webhook extends DefaultModel
{
	protected $table = "webhooks";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [""];

	public $casts = [
		"enabled" => "boolean",
	];

	public $appends = ["code", "url_script"];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function settings()
	{
		return $this->hasMany(WebhookSetting::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function leads()
	{
		return $this->hasMany(Lead::class);
	}

	public function getFEnabledAttribute()
	{
		$icon = getEnabledIcon($this->enabled);
		$text = $this->enabled ? " Ativo" : " Inativo";
		return  "<div class='d-flex flex-row'><span class='mr-2'>$icon</span><span>$text</span></div>";
	}

	public function getLabelAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->name, $this->f_enabled);
	}

	public function getUrlAttribute()
	{
		return config("app.url") . "/api/" . "webhooks" . "/" . $this->token;
	}

	public function requests()
	{
		return $this->hasMany(WebhookRequest::class);
	}

	public function getUrlScriptAttribute()
	{
		return $this->url . "/script.js";
	}
}
