<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class DatesController extends Controller
{
	public function getRanges()
	{
		return [
			"_this_year_" => $this->getThisYear(),
			"_this_month_" => $this->getThisMonth(),
			"_this_week_" => $this->getThisWeek(),
			"_today_" => $this->getToday(),
		];
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
}