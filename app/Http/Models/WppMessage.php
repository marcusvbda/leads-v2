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
		static::addGlobalScope(new PoloScope(with(new static)->getTable()));
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function getFStatusAttribute()
	{
		$options = [
			"waiting" => getEnabledIcon(null) . ' ' . "Aguardando",
		];
		return @$options[$this->status] ?? $this->status;
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
		$phone = @$this->data->teleffone ?? "";
		$phone = preg_replace("/[^0-9]/", "", @$this->data->telefone ?? "");
		return $phone;
	}

	public function getFPhoneAttribute()
	{
		$phone = $this->phone;
		$phoneUtil = PhoneNumberUtil::getInstance();
		$parsed = $phoneUtil->parse($phone, "BR");
		if ($phoneUtil->isValidNumberForRegion($parsed, 'BR')) {
			$lineA = $phoneUtil->format($parsed, PhoneNumberFormat::INTERNATIONAL);
			$lineB = getEnabledIcon(true) . " Válido";
		} else {
			$lineA = $phone;
			$lineB = getEnabledIcon(false) . " Inválido ou não reconhecido";
		}

		return Vstack::makeLinesHtmlAppend($lineA, $lineB);
	}
}
