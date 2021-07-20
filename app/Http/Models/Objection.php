<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Objection extends DefaultModel
{
	protected $table = "objections";
	public $casts  = [
		"need_description" => 'boolean'
	];

	public function getFNeedDescriptionAttribute()
	{
		return $this->need_description ? "<span class='badge badge-pill badge-success'>Sim</span>"  : "<span class='badge badge-pill badge-danger'>Não</span>";
	}
}