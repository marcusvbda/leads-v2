<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use marcusvbda\vstack\Models\Traits\hasCode;
use Auth;
use App\Http\Models\Tenant;
use marcusvbda\vstack\Models\Scopes\TenantScope;
use marcusvbda\vstack\Models\Observers\TenantObserver;
use App\Http\Models\Pivots\{UserPolo};
use App\Http\Models\{Department, Module, UserNotification, Polo};
use App\Http\Models\Scopes\{OrderByScope};

class User extends Authenticatable
{
	use SoftDeletes, Notifiable, hasCode;
	// , HasRoles;
	public $guarded = ['created_at'];
	protected $dates = ['deleted_at'];
	protected $appends = ['code'];
	protected $hashPassword = false;
	public  $casts = [
		"data" => "json",
	];
	public $relations = [];

	public function __construct($hashPassword = true)
	{
		parent::boot();
		$this->hashPassword = $hashPassword;
	}

	public function getRoleNameAttribute()
	{
		$role = data_get(config("roles", []), $this->role . ".title", "");
		return $role;
	}

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
		if (Auth::check()) {
			if (!Auth::user()->hasRole(["super-admin"])) {
				static::observe(new TenantObserver());
				static::addGlobalScope(new TenantScope());
			}
		}
	}

	public function getCodeAttribute()
	{
		return \Hashids::encode($this->id);
	}

	public function receivesBroadcastNotificationsOn()
	{
		return 'App.User.' . $this->id;
	}

	public function setPasswordAttribute($val)
	{
		$this->attributes["password"] = bcrypt($val);
	}

	public function tenant()
	{
		return $this->BelongsTo(Tenant::class);
	}

	public function polos()
	{
		if ($this->isSuperAdmin()) return  Polo::where("id", ">", "0");
		return $this->belongsToMany(Polo::class, UserPolo::class, "user_id", "polo_id");
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	public function getRoleDescriptionAttribute()
	{
		$role = data_get(config("roles", []), $this->role, []);
		return data_get($role, "title", "");
	}


	public function isSuperAdmin()
	{
		return  $this->hasRole(['super-admin']);
	}

	public function userNotifications()
	{
		return $this->hasMany(UserNotification::class, "user_id");
	}

	public function getQyNewNotifications()
	{
		return $this->userNotifications()->isNew()->count();
	}

	public function canAccessModule($module)
	{
		$tenant = $this->tenant;
		return $tenant->storeRemember(__CLASS__ . "@" . __FUNCTION__ . "_" . $module, 60 * 10, function () use ($module) {
			return Module::where("slug", $module)->count() > 0;
		});
	}

	public function hasRole($role)
	{
		$roles = is_array($role) ? $role : [$role];
		return in_array($this->role, $roles);
	}

	public function can($ability, $arguments = [])
	{
		$roles = config("roles", []);
		$permissions = data_get($roles, $this->role . ".permissions", []);
		foreach ($permissions as $permission) {
			if (data_get($permission, 0) === $ability) {
				return true;
			}
		}
		return false;
	}
}
