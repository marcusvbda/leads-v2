<?php

namespace App\Http\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UpsellScope implements Scope
{
	public function apply(Builder $builder, Model $model)
	{
		return $builder->whereType("UP");
	}
}