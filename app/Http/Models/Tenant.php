<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\User;
use App\Http\Models\Scopes\OrderByScope;

class Tenant extends DefaultModel
{
	protected $table = "tenants";
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

	public static function hasTenant() //default true
	{
		return false;
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function polos()
	{
		return $this->hasMany(Polo::class);
	}

	public function getDefaultRatingRulesAttribute()
	{
		return [
			"Possui Nome Completo" => floatval(15),
			"Possui Email" => floatval(20),
			"Possui Telefone Fixo" => floatval(10),
			"Possui Telefone Celular" => floatval(20),
			"Possui Interesse" => floatval(10),
			"Convertido Anteriormente" => floatval(10)
		];
	}
}