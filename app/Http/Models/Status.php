<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Scopes\{OrderByScope};

class Status extends Model
{
	public $guarded = ["created_at"];
	public $cascadeDeletes = [];
	public $restrictDeletes = ['lead'];
	protected $table = "statuses";

	public $casts = [
		"data" => "object"
	];

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable(), "seq"));
	}

	public function lead()
	{
		return $this->hasMany(Lead::class);
	}

	public static function value($val)
	{
		return static::where("value", $val)->firstOrFail();
	}
}