<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;

class WebhookRequest extends DefaultModel
{
	protected $table = "webhook_requests";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [""];

	public $casts = [
		"hide" => "boolean",
		"approved" => "boolean",
		"content" => "json",
	];

	public static function hasTenant()
	{
		return false;
	}

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function webhooks()
	{
		return $this->hasMany(Webhook::class);
	}

	public function getFApprovedAttribute()
	{
		$icon = getEnabledIcon($this->approved);
		$text = $this->approved ? " Aprovado" : " Aguardando";
		return  "<div class='d-flex flex-row'><span class='mr-2'>$icon</span><span>$text</span></div>";
	}
}
