<?php

namespace App\Http\Filters\Users;

use  marcusvbda\vstack\Filter;
use App\Http\Models\Tenant;

class UsersByTenant extends Filter
{

	public $component   = "select-filter";
	public $label       = "Tenant";
	public $index = "tenant_id";
	public $multiple = true;

	public function __construct()
	{
		foreach (Tenant::get() as $row) $this->options[] = (object) ["value" => $row->id, "label" => $row->name];
		parent::__construct();
	}

	public function apply($query, $value)
	{
		$ids = explode($value, ",");
		return $query->whereIn("tenant_id", $ids);
	}
}