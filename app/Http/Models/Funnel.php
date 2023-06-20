<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\User;
use App\Http\Models\Scopes\{OrderByScope, PoloScope};
use Auth;
use Carbon\Carbon;
use marcusvbda\vstack\Vstack;

class Funnel extends DefaultModel
{
	protected $table = "funnels";

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new PoloScope(with(new static)->getTable()));
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
		static::creating(function ($model) {
			if (Auth::check()) {
				$user = Auth::user();
				if (!@$model->user_id) {
					$model->user_id = $user->id;
				}
				if (!@$model->polo_id && $user->polo_id) {
					$model->polo_id = $user->polo_id;
				}
			}
		});
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}
}
