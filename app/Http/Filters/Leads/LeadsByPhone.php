<?php

namespace App\Http\Filters\Leads;

use  marcusvbda\vstack\Filter;


class LeadsByPhone extends Filter
{
    public $component   = "text-filter";
    public $label       = "Telefone";
    public $placeholder = "(00) 00000-0000";
    public $mask = ['(##) ####-####', '(##) #####-####'];
    public $index = "phone";

    public function apply($query, $value)
    {
        $valueWithotMask = preg_replace('/[^0-9]/', '', $value);
        if ($value) {
            return $query->where(function ($q) use ($value, $valueWithotMask) {
                $q->where("data->phones", "like", "%$value%")
                    ->orWhere("data->phones", "like", "%$valueWithotMask%");
            });
        }
        return $query;
    }
}
