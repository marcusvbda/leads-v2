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

	public $appends = ["route", "f_route", "f_time", "resend_route", "cancel_invite"];

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
			"invite_md5" => $this->md5,
			"email" => $this->email
		]);
	}

	public function getFRouteAttribute()
	{
		if (!@$this->id) return;
		return "
            <p class='mb-0'><small class='f-12 badge badge-danger'>Pendente</small></p>
            <p class='mb-0'><a class='f-12 link' href='" . $this->route . "'>Clique para acessar o link</a></p>
        ";
	}

	public function getFTimeAttribute()
	{
		if (!@$this->id) return;
		return  $this->updated_at->diffForHumans();
	}

	public function getResendRouteAttribute()
	{
		if (!@$this->id) return;
		$resend_route = "/admin/usuarios/resend_invite/" . $this->code;
		return "<a class='link' href='" . $resend_route . "'><span class='el-icon-s-promotion mr-2 mr-2'></span>Reenviar Convite</a>";
	}

	public function getCancelInviteAttribute()
	{
		if (!@$this->id) return;
		$resend_route = "/admin/usuarios/cancel_invite/" . $this->code;
		return "<a class='text-danger' href='" . $resend_route . "'><span class='el-icon-close mr-2 mr-2'></span>Cancelar Convite</a>";
	}
}