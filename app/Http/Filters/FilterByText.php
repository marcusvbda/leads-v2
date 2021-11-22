<?php

namespace App\Http\Filters;

use  marcusvbda\vstack\Filter;

class FilterByText extends Filter
{
    public $component   = "text-filter";
    public $label       = "";
    public $index = "";
    public $column = "";
    public $placeholder = "";

    public function __construct($options)
    {
        foreach ($options as $key => $value) {
            $this->{$key} = $value;
        }
        parent::__construct();
    }

    public function apply($query, $value)
    {
        return $query->where($this->column, "like", "%$value%");
    }
}
