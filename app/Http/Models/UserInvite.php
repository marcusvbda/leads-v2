<?php

namespace App\Http\Models;

use App\Http\Models\SuperAdminAccessModel;

class UserInvite extends SuperAdminAccessModel
{
	protected $table = "user_invites";

	public static function boot()
	{
		parent::boot();
		self::created(function ($model) {
			$model->md5 = md5($model->code);
			$model->saveOrFail();
		});
	}

	public $appends = ["code", "route", "f_route", "f_time", "cancel_invite"];

	public $casts = [
		"data" => "object"
	];

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getRouteAttribute()
	{
		return  route("user.create", [
			"tenant_id" => $this->tenant->code,
			"invite_md5" => $this->md5
		]);
	}

	public function getFRouteAttribute()
	{
		if (!@$this->id) return;
		return "
            <p class='mb-0'><small class='text-red-700 text-xs'>Pendente</small></p>
            <p class='mb-0'><a class='text-xs' href='" . $this->route . "'>Clique para acessar o link</a></p>
        ";
	}

	public function getFTimeAttribute()
	{
		if (!@$this->id) return;
		return  $this->updated_at->diffForHumans();
	}

	public function getCancelInviteAttribute()
	{
		if (!@$this->id) return;
		$resend_route = "/admin/usuarios/cancel_invite/" . $this->code;
		return "<a class='text-red-700 text-xs' href='" . $resend_route . "'><span class='el-icon-close mr-2'></span>Cancelar Convite</a>";
	}
}
