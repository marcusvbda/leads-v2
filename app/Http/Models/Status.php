<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
	public $guarded = ["created_at"];
	public $cascadeDeletes = [];
	public $restrictDeletes = ['lead'];
	protected $table = "statuses";

	public $casts = [
		"data" => "object"
	];

	public function lead()
	{
		return $this->hasMany(Lead::class);
	}

	public static function value($val)
	{
		return static::where("value", $val)->firstOrFail();
	}
}