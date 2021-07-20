<?php

namespace App\Http\Models\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class PoloScope implements Scope
{
	private $polo = null;
	private $has_global = null;
	private $table = "";

	public function __construct($table, $has_global = false)
	{
		$this->table = $table;
		$this->has_global = $has_global;
		if (Auth::check()) $this->polo = Auth::user()->polo;
	}

	public function apply(Builder $builder, Model $model)
	{
		if (@$this->polo->id) {
			@$builder->where(function ($q) {
				$column = $this->table . ".polo_id";
				$q->where($column, $this->polo->id);
				if ($this->has_global)	$q->orWhereNull($column);
			});
		}
	}
}