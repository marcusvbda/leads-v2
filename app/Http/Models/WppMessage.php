<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\{OrderByScope, PoloScope};
use App\User;
use libphonenumber\{PhoneNumberUtil, PhoneNumberFormat};
use marcusvbda\vstack\Vstack;

class WppMessage extends DefaultModel
{
	protected $table = "wpp_messages";

	public $casts = [
		"data" => "object",
	];

	public static function boot()
	{
		parent::boot();
		$table = with(new static)->getTable();
		static::addGlobalScope(new PoloScope($table, true));
		static::addGlobalScope(new OrderByScope($table));
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function wpp_session()
	{
		return $this->belongsTo(WppSession::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getFStatusAttribute()
	{
		return static::makeStatusHTML($this->status);
	}

	public static function makeStatusHTML($status)
	{
		$options = [
			"waiting" => getEnabledIcon(null) . ' ' . "Aguardando",
			"error" => getEnabledIcon(false) . ' ' . "Erro",
			"sent" => getEnabledIcon(true) . ' ' . "Enviado",
			"sending" => '<div class="d-flex flex-row align-items-center small-loading-balls">' . getEnabledIcon('loading') . ' ' . "Processando" . '</div>',
		];
		return @$options[$status] ?? $status;
	}

	public function getSocketFStatusAttribute()
	{
		return "<wpp-status-check status='{$this->f_status}' :id='{$this->id}' current_status='{$this->status}'></wpp-status-check>";
	}

	public function getFormatedMessageAttribute()
	{
		$message = @$this->data->mensagem ?? "";
		$keys = array_keys((array)$this->data);
		foreach ($keys as $key) {
			if (!in_array($key, ["mensagem"])) {
				$message = str_replace("{" . $key . "}", data_get($this->data, $key), $message);
			}
		}
		return $message;
	}

	public function getMessageCutedAttribute()
	{
		$message = @$this->formated_message;
		$message = substr($message, 0, 35) . ((strlen($message) > 32) ? " ..." : '');
		return $message;
	}

	public function getPhoneAttribute()
	{
		$phone = @$this->data->telefone ?? "";
		$phone = preg_replace("/[^0-9]/", "", $phone ?? "");
		return $phone;
	}

	public function getFPhoneAttribute()
	{
		$phone = $this->phone;
		$phoneUtil = PhoneNumberUtil::getInstance();
		$parsed = $phoneUtil->parse($phone, "BR");
		if ($phoneUtil->isValidNumberForRegion($parsed, 'BR')) {
			$lineA = $phoneUtil->format($parsed, PhoneNumberFormat::INTERNATIONAL);
			$lineB = getEnabledIcon(true) . " Número Válido";
		} else {
			$lineA = $phone;
			$lineB = getEnabledIcon(false) . " Número Inválido ou não reconhecido";
		}

		return Vstack::makeLinesHtmlAppend($lineA, $lineB);
	}
}
