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
	public $appends = ["f_created_at", "f_read_at"];

	public static function boot()
	{
		parent::boot();
		$socket_event = function ($model) {
			broadcast(new WebSocketEvent("App.User." . $model->user_id, "notifications.user", [
				"qty" => @$model->user ? $model->user->getQyNewNotifications() : 0
			]));
			broadcast(new WebSocketEvent("App.Polo." . $model->polo_id, "notifications.user", [
				"qty" =>  @$model->polo ? $model->polo->getQyNewNotifications() : 0
			]));
			broadcast(new WebSocketEvent("App.Tenant." . $model->tenant_id, "notifications.user", [
				"qty" =>  @$model->tenant ? $model->tenant->getQyNewNotifications() : 0
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