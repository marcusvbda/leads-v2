<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
	protected $table = "email_templates";
	public $casts = ["body" => "json"];
}