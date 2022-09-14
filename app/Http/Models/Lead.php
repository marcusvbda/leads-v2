<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\User;
use App\Http\Models\Scopes\{OrderByScope, PoloScope};
use Auth;
use Carbon\Carbon;
use marcusvbda\vstack\Vstack;

class Lead extends DefaultModel
{
	public $resource_id = "leads";
	protected $table = "leads";
	// public $cascadeDeletes = [];
	// public $restrictDeletes = [""];

	public $casts = [
		"data" => "object"
	];

	public $appends = [
		"code", "name", "city", "label", "f_status", "email", "profession", "f_last_conversion", "cellphone_number",
		"phone_number", "obs", "f_created_at", "objection", "comment", "interest", "f_status_badge",
		"f_birthdate", "age", "f_last_conversion_date", "api_ref_token", "other_objection", "conversions",
		"tries", "lead_api", "f_rating", "f_schedule", "f_updated_at"
	];


	public static function boot()
	{
		parent::boot();
		static::addGlobalScope(new PoloScope(with(new static)->getTable()));
		static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
		static::creating(function ($model) {
			if (Auth::check()) {
				$user = Auth::user();
				if (!@$model->user_id) {
					$model->user_id = $user->id;
				}
				if (!@$model->polo_id && $user->polo_id) {
					$model->polo_id = $user->polo_id;
				}
			}
			if (!@$model->status_id) {
				$model->status_id = Status::value("waiting")->id;
			}
		});
	}

	public function getOriginSourcesBadge()
	{
		$sources = $this->data->source ?? [];
		$html = "";
		foreach ($sources as $source) {
			$html .= "<span class='badge badge-secondary'>{$source}</span> ";
		}
		return $html;
	}

	public function getLabelAttribute()
	{
		$vstack_tags = "<resource-tags-input class='f-12 mt-1' :default_tags='row.content.tags' only_view />";
		$tags_origin = $this->getOriginSourcesBadge();
		$name = $this->name ?? "Nome não informado";
		$department = $this->department;
		if ($department) {
			$department = "<b class='mr-1'>Departamento :</b>" . $department->name;
		}

		$responsible = $this->responsible;
		if ($responsible &&  @$responsible?->name) {
			$responsible = "<b class='mr-1'>Responsável :</b>" . $responsible->name;
		}

		$objection = $this->objection;
		if ($objection && is_string($objection)) {
			$objection = "<b class='mr-1'>Objeção :</b>" . $objection;
		}

		$other_objection = $this->other_objection;
		if ($other_objection) {
			$other_objection = "<b class='mr-1'>Descrição da objeção :</b>" . $other_objection;
		}

		return Vstack::makeLinesHtmlAppend($name, $this->f_rating, $tags_origin, $responsible, $department, $objection, $other_objection, $vstack_tags);
	}

	public function department()
	{
		return $this->belongsTo(Department::class);
	}

	// getters
	public function getFRatingAttribute()
	{
		$rating = $this->rating;
		return "<el-rate :value='$rating' :colors='[`#99A9BF`, `#F7BA2A`, `#FF9900`]' disabled></el-rate>";
	}

	public function getRatingAttribute()
	{
		$tenant = $this->tenant;
		$default = (array) $tenant->default_rating_rules;
		$rating_rules = array_merge($default, (array)@$tenant->data->rating_rules ?? $default);
		$total = array_sum($rating_rules);
		$points = 0;
		if (count(explode(" ", $this->name)) > 1) {
			$points += floatval($rating_rules['Possui Nome Completo']);
		}
		if ($this->email) {
			$points += floatval($rating_rules['Possui Email']);
		}
		if ($this->phone_number) {
			$points += floatval($rating_rules['Possui Telefone Fixo']);
		}
		if ($this->cellphone_number) {
			$points += floatval($rating_rules['Possui Telefone Celular']);
		}
		if ($this->interest) {
			$points += floatval($rating_rules['Possui Interesse']);
		}
		if (count($this->conversions) > 0) {
			$points += floatval($rating_rules['Convertido Anteriormente']);
		}
		$rating = round((($points * 5) / $total), 2);
		return $rating;
	}

	public function getLeadApiAttribute()
	{
		return $this->data->lead_api ?? [];
	}

	public function getTriesAttribute()
	{
		return $this->data->tries ?? [];
	}

	public function getConversionsAttribute()
	{
		return $this->data->log ?? [];
	}

	public function getLastConversionAttribute()
	{
		return current($this->conversions);
	}

	public function getFLastConversionDateAttribute()
	{
		$last_conversion = $this->f_last_conversion;
		return "Última Conversão : " . $last_conversion;
	}

	public function getFStatusBadgeAttribute()
	{
		$status = $this->status;
		return "<b class='status-color {$status->value}'>{$status->name}</b>";
	}

	public function status()
	{
		return $this->belongsTo(Status::class, "status_id");
	}

	public function getFStatusAttribute()
	{
		return $this->status->name;
	}

	public function getEmailAttribute()
	{
		return @$this->data->email;
	}

	public function getEmailUrlAttribute()
	{
		return "<email-url type='email' value='{$this->email}'>{$this->email}</email-url>";
	}

	public function getContactAttribute()
	{
		return Vstack::makeLinesHtmlAppend($this->email_url, $this->cellphone_number, $this->phone_number);
	}

	public function getPhonesUrlAttribute()
	{
		$html = [];
		$cell = $this->cellphone_number;
		$phone = $this->phone_number;
		if (@$phone) {
			$html[] = "<span>{$phone}</span>";
		}
		if (@$cell) {
			$html[] = "<email-url type='wpp'  value='{$cell}'>{$cell}</email-url>";
		}
		$html = implode('  ', $html);
		return "<div class='d-flex flex-column text-center'>{$html}</div>";
	}

	public function getFCreatedAtAttribute()
	{
		$created = $this->created_at;
		return formatDate($created);
	}

	public function getOriginAttribute()
	{
		return $this->webhook_id ? $this->webhook->name : $this->user->name;
	}

	public function getNameAttribute()
	{
		return @$this->data->name;
	}

	public function getBirthdateAttribute()
	{
		return @$this->data->birthdate;
	}

	public function getFBirthdateAttribute()
	{
		$birthdate = @$this->birthdate;
		if (!$birthdate) return;
		return Carbon::create($birthdate)->format("d/m/Y");
	}

	public function getAgeAttribute()
	{
		$birthdate = @$this->birthdate;
		if (!$birthdate) return;
		return Carbon::create($birthdate)->age;
	}

	public function getObjectionAttribute()
	{
		return @$this->data->objection;
	}

	public function getOtherObjectionAttribute()
	{
		return @$this->data->other_objection;
	}

	public function getCommentAttribute()
	{
		return @$this->data->comment;
	}

	public function getFLastConversionAttribute()
	{
		$last_conversion = $this->last_conversion;
		if (!@$last_conversion->date || !$last_conversion->timestamp) return "Nunca Convertido";
		return $last_conversion->date . " - " . @$last_conversion->timestamp;
	}

	public function getCellphoneNumberAttribute()
	{
		return formatPhoneNumber(@$this->data->phones[0]);
	}

	public function getPhoneNumberAttribute()
	{
		return formatPhoneNumber(@$this->data->phones[1]);
	}

	public function getObsAttribute()
	{
		return @$this->data->obs;
	}

	public function getCityAttribute()
	{
		return @$this->data->city;
	}

	public function getZipcodeAttribute()
	{
		return @$this->data->zipcode;
	}

	public function getDistrictAttribute()
	{
		return @$this->data->district;
	}

	public function getAddressNumberAttribute()
	{
		return @$this->data->address_number;
	}

	public function getProfessionAttribute()
	{
		return @$this->data->profession;
	}

	public function getComplementaryAttribute()
	{
		return @$this->data->complementary;
	}

	public function getInterestAttribute()
	{
		return @$this->data->interest;
	}

	public function getApiRefTokenAttribute()
	{
		return @$this->data->api_ref_token;
	}

	public function getScheduleAttribute()
	{
		return @$this->data->schedule;
	}

	public function getFscheduleAttribute()
	{
		return @$this->schedule  ? Carbon::create($this->schedule)->format("d/m/Y - H:i:s") : false;
	}


	// getters

	// setters

	public function setTriesAttribute($value)
	{
		setModelDataValue($this, "tries", $value);
	}

	public function setConversionsAttribute($value)
	{
		setModelDataValue($this, "log", $value);
	}

	public function setScheduleAttribute($value)
	{
		setModelDataValue($this, "schedule", $value);
	}

	public function setApiRefTokenAttribute($value)
	{
		setModelDataValue($this, "api_ref_token", $value);
	}

	public function setBirthdateAttribute($value)
	{
		setModelDataValue($this, "birthdate", $value);
	}

	public function setEmailAttribute($value)
	{
		setModelDataValue($this, "email", $value);
	}

	public function setPhoneNumberAttribute($value)
	{
		setModelDataValue($this, "phones", [$this->cellphone_number, $value]);
	}

	public function setCellphoneNumberAttribute($value)
	{
		setModelDataValue($this, "phones", [$value, $this->phone_number]);
	}

	public function getPrimaryPhoneNumberAttribute()
	{
		return @$this->cellphone_number ? $this->cellphone_number : $this->phone_number;
	}

	public function getSecondaryPhoneNumberAttribute()
	{
		return (@$this->cellphone_number &&  @$this->cellphone_number != @$this->phone_number) ? $this->phone_number : "";
	}

	public function setNameAttribute($value)
	{
		setModelDataValue($this, "name", $value);
	}

	public function setProfessionAttribute($value)
	{
		setModelDataValue($this, "profession", $value);
	}

	public function setCommentAttribute($value)
	{
		setModelDataValue($this, "comment", $value);
	}

	public function setObjectionAttribute($value)
	{
		setModelDataValue($this, "objection", $value);
	}

	public function setOtherObjectionAttribute($value)
	{
		setModelDataValue($this, "other_objection", $value);
	}

	public function setObsAttribute($value)
	{
		setModelDataValue($this, "obs", $value);
	}

	public function setCityAttribute($value)
	{
		setModelDataValue($this, "city", $value);
	}

	public function setZipcodeAttribute($value)
	{
		setModelDataValue($this, "zipcode", $value);
	}

	public function setDistrictAttribute($value)
	{
		setModelDataValue($this, "district", $value);
	}

	public function setComplementaryAttribute($value)
	{
		setModelDataValue($this, "complementary", $value);
	}

	public function setAddressNumberAttribute($value)
	{
		setModelDataValue($this, "address_number", $value);
	}

	public function setInterestAttribute($value)
	{
		setModelDataValue($this, "interest", $value);
	}
	// setters

	// relations

	public function tenant()
	{
		return $this->belongsTo(Tenant::class);
	}

	public function webhook()
	{
		return $this->belongsTo(Webhook::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function polo()
	{
		return $this->belongsTo(Polo::class);
	}

	public function webhook_request()
	{
		return $this->belongsTo(WebhookRequest::class);
	}
	// relations


	public static function logConversions($lead, $now, $user, $new_status = null, $history = null, $just_return = false)
	{
		$conversions = $lead->conversions;
		if ($history) {
			$desc = $history;
		} else {
			if (!$new_status || ($lead->status_id == @$new_status->id)) {
				$desc = "Converteu no funil de produção de sem alteração de status";
			} else {
				$desc = "Converteu no funil de produção de <b>" . $lead->status->name . "</b> para <b>" . $new_status->name . "</b>";
			}
		}
		array_unshift($conversions, [
			"obs" => null,
			"date" =>  $now->format("d/m/Y"),
			"desc" => $desc,
			"user" => $user->name,
			"timestamp" => $now->format("H:i:s")
		]);
		if ($just_return) {
			return $conversions;
		}
		$lead->conversions = $conversions;
		return $lead;
	}

	public function responsible()
	{
		return $this->belongsTo(User::class, "responsible_id");
	}
}
