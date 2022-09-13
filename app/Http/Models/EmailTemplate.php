<?php

namespace App\Http\Models;

use App\Http\Models\Scopes\OrderByScope;
use App\Http\Models\Scopes\PoloScope;
use Auth;
use marcusvbda\vstack\Models\DefaultModel;
use marcusvbda\vstack\Services\SendMail;

class EmailTemplate extends DefaultModel
{
    protected $table = "email_template";

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PoloScope(with(new static)->getTable()));
        static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
        static::creating(function ($model) {
            $user = Auth::user();
            $model->polo_id = $user->polo_id;
        });
    }

    public function polo()
    {
        return $this->belongsTo(Polo::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function send($data)
    {
        $address = data_get($data, "address");
        $template_process = data_get($data, "template_process");

        $processed_template = $this->body;

        if ($template_process) {
            $process_context = data_get($data, "process_context");
            $processed_template = process_template($processed_template, $process_context);
        }

        return SendMail::to($address, $this->subject, $processed_template);
    }
}
