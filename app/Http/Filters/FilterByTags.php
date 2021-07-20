<?php

namespace App\Http\Filters;

use  marcusvbda\vstack\Filter;
use marcusvbda\vstack\Models\TagRelation;
use Auth;

class FilterByTags extends Filter
{
	public $component   = "select-filter";
	public $label       = "Tags";
	public $placeholder = "";
	public $index = "sales_by_tags";
	public $table = "";
	public $model = "";
	public $user = null;

	public function __construct($model)
	{
		$this->model = $model;
		$this->table = (new $this->model)->getTable();
		$this->user = Auth::user();
		$this->options = TagRelation::where("resource_tags_relation.model", $this->model)
			->join("resource_tags", "resource_tags.id", "=", "resource_tags_relation.resource_tag_id")
			->groupBy("resource_tags_relation.resource_tag_id")
			->select("resource_tags.id as value", "resource_tags.name as label")
			->where("resource_tags.tenant_id", $this->user->tenant_id)
			->where("resource_tags.model", $this->model)
			->get();
		parent::__construct();
	}

	public function apply($query, $value)
	{
		return $query
			->join("resource_tags_relation", "resource_tags_relation.relation_id", "=", $this->table . ".id")
			->where("resource_tags_relation.model", $this->model)
			->where("resource_tags_relation.resource_tag_id", $value);
	}
}