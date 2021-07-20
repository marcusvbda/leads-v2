<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class AutomationSentEmail extends Model
{
	protected $table = "automation_sent_emails";
	public $guarded = ["created_at"];
	public $casts = ["email_content" => "object"];

	public function custom_automation()
	{
		return $this->belongsTo(CustomAutomation::class);
	}

	public function lead()
	{
		return $this->belongsTo(Lead::class);
	}
}