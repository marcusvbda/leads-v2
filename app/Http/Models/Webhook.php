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

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function leads()
	{
		return $this->hasMany(Lead::class);
	}

	public function getLabelAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->name, $this->enabled ? " Ativo" : " Inativo");
	}

	public function getUrlAttribute()
	{
		return config("app.url") . "/" . "webhooks" . "/" . $this->token;
	}

	public function requests()
	{
		return $this->hasMany(WebhookRequest::class);
	}
}