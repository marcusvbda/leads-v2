<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class UserNotification extends Model
{
	use SoftDeletes;
	protected $table = "user_notifications";
	public $guarded = ["created_at"];
	public $appends = ["f_created_at", "f_read_at"];

	public static function hasTenant()
	{
		return false;
	}

	public $casts = [
		"data" => "object",
		"read_at" => "datetime"
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function scopeIsNew($query)
	{
		return $query->where('read_at', null);
	}

	public function getFCreatedAtAttribute()
	{
		return formatDate($this->created_at);
	}

	public function getFReadAtAttribute()
	{
		return formatDate($this->read_at);
	}
}
