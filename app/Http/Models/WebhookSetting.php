<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookSetting extends Model
{
    public $guarded = ["created_at"];
    protected $table = "webhook_settings";

    public function getJsonIndexesAttribute()
    {
        $explodeIndexes = explode("|", $this->indexes);
        $newObject = [];
        foreach ($explodeIndexes as $index) {
            $explodeIndex = explode("=>", $index);
            $newObject[$explodeIndex[0]] = $explodeIndex[1];
        }
        return $newObject;
    }

    public function webhook()
    {
        return $this->belongsTo(Webhook::class);
    }


    public function polo()
    {
        return $this->belongsTo(Polo::class);
    }
}