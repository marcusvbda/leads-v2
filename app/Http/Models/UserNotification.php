<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use marcusvbda\vstack\Events\WebSocketEvent;
use App\User;

class UserNotification extends Model
{
	use SoftDeletes;
	protected $table = "user_notifications";
	public $guarded = ["created_at"];
	public $appends = ["f_created_at"];

	public static function boot()
	{
		parent::boot();
		$socket_event = function ($model) {
			broadcast(new WebSocketEvent("App.User." . $model->user_id, "notifications.user", [
				"qty" => $model->user->getQyNewNotifications()
			]));
		};
		static::created(function ($model) use ($socket_event) {
			$socket_event($model);
		});
		static::updated(function ($model) use ($socket_event) {
			$socket_event($model);
		});
	}

	public static function hasTenant()
	{
		return false;
	}

	public $casts = [
		"data" => "object"
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function scopeIsNew($query)
	{
		return $query->where('new', true);
	}

	public function getFCreatedAtAttribute()
	{
		return formatDate($this->created_at);
	}
}