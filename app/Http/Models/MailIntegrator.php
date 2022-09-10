<?php

namespace App\Http\Models;

use App\Http\Models\Scopes\OrderByScope;
use App\Http\Models\Scopes\PoloScope;
use marcusvbda\vstack\Models\DefaultModel;
use Auth;

class MailIntegrator extends DefaultModel
{
	protected $table = "mail_integrators";

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new PoloScope(with(new static)->getTable()));
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
		static::creating(function ($model) {
			$user = Auth::user();
			$model->polo_id = $user->polo_id;
		});
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}
}
