<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookSetting extends Model
{
    public $guarded = ["created_at"];
    protected $table = "webhook_settings";


    public function webhook()
    {
        return $this->belongsTo(Webhook::class);
    }


    public function polo()
    {
        return $this->belongsTo(Polo::class);
    }
}