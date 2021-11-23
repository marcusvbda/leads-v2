<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserIntegrator extends Model
{
    public $guarded = ["created_at"];

    public $casts = [
        "data" => "object",
        "enabled" => "boolean"
    ];
}
