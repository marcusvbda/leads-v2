<?php

namespace App\Http\Filters\Leads;

use  marcusvbda\vstack\Filter;

class LeadsByName extends Filter
{
	public $component   = "text-filter";
	public $label       = "Nome";
	public $index = "name";

	public function apply($query, $value)
	{
		return $query->where("data->name", "like", "%$value%");
	}
}