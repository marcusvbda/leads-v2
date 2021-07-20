<?php

namespace App\Http\Filters\Leads;

use  marcusvbda\vstack\Filter;
use App\Http\Models\Status;

class LeadsByStatus extends Filter
{

	public $component   = "select-filter";
	public $label       = "Status";
	public $index = "status";
	public $_options = [];

	public function __construct()
	{
		$this->options = Status::selectRaw("id as value,name as label")->get();
		parent::__construct();
	}

	public function apply($query, $value)
	{
		return $query->whereIn("status_id", Status::findOrFail($value)->sub_status()->pluck("id"));
	}
}