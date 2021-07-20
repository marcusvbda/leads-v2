<?php

namespace App\Http\Filters\Leads;

use  marcusvbda\vstack\Filter;
use Carbon\Carbon;

class LeadsByCreatedDate extends Filter
{
	public $component   = "rangedate-filter";
	public $label       = "Data de Criação";
	public $index = "created_at";
	public $value_format = "dd/MM/yyyy";

	public function apply($query, $value)
	{
		$dates = explode(",", $value);
		if (count($dates) < 2) return $query;
		$dates = array_map(function ($x) {
			return Carbon::createFromFormat('d/m/Y', $x)->format("Y-m-d");
		}, $dates);
		return $query->where(function ($q) use ($dates) {
			$q->whereRaw(queryBetweenDates("leads.created_at", $dates));
		});
	}
}