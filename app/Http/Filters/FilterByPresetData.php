<?php

namespace App\Http\Filters;

use  marcusvbda\vstack\Filter;
use Carbon\Carbon;

class FilterByPresetData extends Filter
{
    public $component   = "custom-filter";
    public $label       = "Data de Criação";
    public $index = "created_at";
    public $action = null;

    public  $_options = [
        "hoje" => "getToday",
        "ontem" => "getYesterday",
        "ultimos 7 dias" => "getLastSevenDays",
        "ultimos 14 dias" => "getLastFourteenDays",
        "ultimos 30 dias" => "getLastThirthDays",
        "esta semana" => "getThisWeek",
        "este mês" => "getThisMonth",
        "este ano" => "getThisYear",
    ];

    public function __construct($label = "Período", $index = "created_at", $action = null)
    {
        $this->label = $label;
        $this->index = $index;
        $this->element = "
            <custom-preset-date-filter @on-submit='showConfirm' index='" . $this->index . "' :filter='filter'/>
        ";

        if (@$action) {
            $this->action = $action;
        } else {
            $this->action = function ($query, $value) {
                if ($value == "todos") {
                    return $query;
                }
                $dates = $this->getDates($value);
                return  $query->whereRaw(queryBetweenDates("created_at", $dates));
            };
        }
        parent::__construct();
    }


    public function apply($query, $value)
    {
        $action = $this->action;
        return  $action($query, $value);
    }

    public function getDates($value)
    {
        $values = explode(",", $value);
        if (in_array($values[0], array_keys($this->_options))) {
            return $this->{$this->_options[$value]}();
        }
        return $values;
    }

    public function getLastThirthDays()
    {
        $starts = $this->format(Carbon::now()->subDays(30));
        $ends = $this->format(Carbon::now());
        return [$starts, $ends];
    }

    public function getLastFourteenDays()
    {
        $starts = $this->format(Carbon::now()->subDays(14));
        $ends = $this->format(Carbon::now());
        return [$starts, $ends];
    }

    public function getLastSevenDays()
    {
        $starts = $this->format(Carbon::now()->subDays(7));
        $ends = $this->format(Carbon::now());
        return [$starts, $ends];
    }

    public function getYesterday()
    {
        $yesterday = $this->format(Carbon::now()->subDays(1));
        return [$yesterday, $yesterday];
    }

    public function getToday()
    {
        $today = $this->format(Carbon::now());
        return [$today, $today];
    }

    public function getThisWeek()
    {
        return [
            $this->format(Carbon::now()->startOfWeek()->subDays(1)),
            $this->format(Carbon::now()->endOfWeek()->subDays(1)),
        ];
    }

    public function getThisYear()
    {
        $year = Carbon::now()->format("Y");
        return [
            $this->format(Carbon::create("01-01-$year")),
            $this->format(Carbon::create("31-12-$year"))
        ];
    }

    public function getThisMonth()
    {
        return [
            $this->format(Carbon::now()->startOfMonth()),
            $this->format(Carbon::now()->endOfMonth()),
        ];
    }

    private function format($date)
    {
        return $date->format("Y-m-d");
    }

    public static function getAllDates()
    {
        $filter = new FilterByPresetData;
        $options_dates = [];
        foreach ($filter->_options as $key => $value) {
            $options_dates[$key] = $filter->{$value}();
        }
        return $options_dates;
    }
}
