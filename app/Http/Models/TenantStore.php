<?php

namespace  App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TenantStore extends Model
{
    public $guarded = ["created_at"];
    public $tables = "tenant_stores";
    public $casts = [
        "data" => "object"
    ];
}
