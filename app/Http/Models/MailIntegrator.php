<?php

namespace App\Http\Models;

use App\Http\Models\Scopes\OrderByScope;
use App\Http\Models\Scopes\PoloScope;
use marcusvbda\vstack\Models\DefaultModel;
use Auth;
use Illuminate\Support\Facades\Config;

class MailIntegrator extends DefaultModel
{
	protected $table = "mail_integrators";

	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new PoloScope(with(new static)->getTable()));
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
		static::creating(function ($model) {
			$user = Auth::user();
			$model->polo_id = $user->polo_id;
		});
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function defineConfigs()
	{
		Config::set('mail.driver', "teste");
		Config::set("mail.host", "smtp.sendgrid.net");
		Config::set("mail.port", 587);
		Config::set("mail.username", "apikey");
		Config::set("mail.password", $this->hash_password);
		Config::set("mail.encryption", "tls");
		Config::set("mail.from.address", $this->email);
		Config::set("mail.from.name", $this->from_name);
	}
}
