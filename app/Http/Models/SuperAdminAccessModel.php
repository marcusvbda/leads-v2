<?php

namespace  App\Http\Models;

use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use Auth;

class SuperAdminAccessModel extends DefaultModel
{
	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));

		if (Auth::check()) {
			if (!Auth::user()->hasRole(["super-admin", "callcenter"])) {
				static::observe(new TenantObserver());
				static::addGlobalScope(new TenantScope());
			}
		}
	}

	public static function hasTenant()
	{
		return false;
	}
}