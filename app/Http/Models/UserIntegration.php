<?php

namespace App\Http\Models;

use marcusvbda\vstack\Models\DefaultModel;
use App\Http\Models\Scopes\OrderByScope;
use Illuminate\Support\Arr;
use marcusvbda\vstack\Vstack;

class UserIntegrator extends DefaultModel
{
    protected $table = "user_integrators";
    // public $cascadeDeletes = [];
    // public $restrictDeletes = [""];

    public $casts = [
        "data" => "object",
        "enabled" => "boolean"
    ];

    public $hidden = [
        "secret"
    ];

    const _ENV_OPTIONS_ = [
        ["value" => "homologation", "label" => "Homologação"],
        ["value" => "production", "label" => "Produção"],
    ];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope(with(new static)->getTable()));
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getFEnabledAttribute()
    {
        $icon = getEnabledIcon($this->enabled);
        $text = $this->enabled ? " Ativo" : " Inativo";
        return  "<div class='d-flex flex-row'><span class='mr-2'>$icon</span><span>$text</span></div>";
    }

    public function getLabelAttribute()
    {
        return Vstack::makeLinesHtmlAppend($this->name, $this->f_enabled);
    }

    private function getValueFromConst($index, $options)
    {
        $found = array_filter($options, function ($x) use ($index) {
            if (Arr::get($x, "value") == $index) return $x;
        });
        return Arr::get(current($found), "label");
    }

    public function getFEnvAttribute()
    {
        return $this->getValueFromConst($this->env, static::_ENV_OPTIONS_);
    }
}
