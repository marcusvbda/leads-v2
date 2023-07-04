<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\{OrderByScope, PoloScope};
use Auth;
use marcusvbda\vstack\Vstack;

class Lead extends DefaultModel
{
	public $resource_id = "leads";
	protected $table = "leads";

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

	public function getLabelAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->name, $this->email);
	}

	public function campaigns()
	{
		return $this->belongsToMany(Campaign::class, 'campaign_leads', 'lead_id', 'campaign_id');
	}

	public function getampaignIdsAttribute()
	{
		return $this->campaigns->pluck("id");
	}
}
