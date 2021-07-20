<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\User;
use App\Http\Models\Scopes\OrderByScope;
use App\Http\Models\Pivots\UserPolo;

class Polo extends DefaultModel
{
	protected $table = "polos";
	// public $cascadeDeletes = [];
	public $restrictDeletes = ["users"];

	public $casts = [
		"data" => "object",
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function getAllPossibleUsers()
	{
		return User::whereIn("id", UserPolo::where("polo_id", $this->id)->pluck("user_id")->ToArray())->get();
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getHeadAttribute()
	{
		return @$this->data->head ? true : false;
	}

	public function getFHeadAttribute()
	{
		return $this->head ? "Polo Sede" : "Polo Comum";
	}

	public function getCityAttribute()
	{
		return $this->data->city;
	}

	public function setCityAttribute($value)
	{
		$data =  @$this->attributes["data"] ? json_decode($this->attributes["data"]) : (object)[];
		$data->city = $value;
		$this->attributes["data"] = json_encode($data);
	}

	public function setHeadAttribute($value)
	{
		$data =  @$this->attributes["data"] ? json_decode($this->attributes["data"]) : (object) [];
		$data->head = $value;
		$this->attributes["data"] = json_encode($data);
	}
}