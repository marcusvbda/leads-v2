<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;

class Email extends DefaultModel
{
	protected $table = "emails";
	public $casts = ["body" => "json"];
}