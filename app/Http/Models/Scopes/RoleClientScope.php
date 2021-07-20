<?php

namespace App\Http\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class RoleClientScope implements Scope
{
	private $protected_roles = [];
	public function __construct($roles)
	{
		$this->protected_roles = $roles;
	}

	public function apply(Builder $builder, Model $model)
	{
		if (Auth::check()) @$builder->whereNotIn("name", $this->protected_roles)->where("id", "!=", Auth::user()->roles[0]->id);
	}
}