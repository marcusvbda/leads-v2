<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\User;
use App\Http\Models\Scopes\OrderByScope;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

	public function tenantNotifications()
	{
		return $this->hasMany(UserNotification::class, "tenant_id");
	}

	public function getQyNewNotifications()
	{
		return $this->tenantNotifications()->isNew()->count();
	}

	public function stores()
	{
		return $this->hasMany(TenantStore::class);
	}

	public function storeRemember($type, $timeout, $callback)
	{
		$now = Carbon::now();
		$user = Auth::user();
		$formated_now = $now->format("Y-m-d H:i:s");
		$query = "DATE_ADD(created_at, INTERVAL $timeout second) >= '$formated_now'";
		$store = $this->stores()->where("user_id", $user->id)->where("type", "=", $type)->whereRaw($query)->first();
		$data = [];
		if (@$store) {
			$value = data_get($store->data, "value.0");
			$type = @data_get($store->data, "type");
			$actions = [
				"boolean" => function ($val) {
					return boolval($val);
				},
				"array" => function ($val) {
					return (array)$val;
				},
				"integer" => function ($val) {
					return intval($val);
				},
				"double" => function ($val) {
					return floatval($val);
				}
			];
			$data = @$actions[$type] ? $actions[$type]($value) : $value;
		} else {
			$data = @$callback();
			$store = $this->stores()->firstOrNew(["type" => $type]);
			$type = gettype($data);
			$store->data = ["value" => [$data], "type" => $type];
			$store->user_id = $user->id;
			$store->created_at = $now;
			$store->save();
		}
		return $data;
	}

	public function clearStores()
	{
		$user = Auth::user();
		if ($user) {
			$this->stores()->where("user_id", $user->id)->delete();
		} else {
			$this->stores()->delete();
		}
	}
}
