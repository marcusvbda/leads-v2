<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class DashboardController extends Controller
{
	private $divisor = 5;

	public function index()
	{
		return view('admin.index');
	}

	public function getData($action, Request $request)
	{
		return $this->{$action}($request);
	}

	protected function polosQty()
	{
		$data = DB::select("select count(*) as qty from polos where tenant_id = :tenant_id", ["tenant_id" => Auth::user()->tenant_id]);
		return response()->json($data[0]->qty);
	}

	protected function departmentesQty()
	{
		$data = DB::select("select count(*) as qty from departments where tenant_id = :tenant_id", ["tenant_id" => Auth::user()->tenant_id]);
		return response()->json($data[0]->qty);
	}

	protected function usersQty()
	{
		$data = DB::select("select count(*) as qty from users where tenant_id = :tenant_id", ["tenant_id" => Auth::user()->tenant_id]);
		return response()->json($data[0]->qty);
	}

	protected function newLeadsQty(Request $request)
	{
		$data = DB::select("select count(*) as qty from leads where tenant_id = :tenant_id and DATE(created_at) = DATE(:created_at)", [
			"tenant_id" => Auth::user()->tenant_id,
			"created_at" => $request["today"]
		]);
		return response()->json($data[0]->qty);
	}

	// protected function new_leads(Request $request)
	// {
	// 	$qty_divides = $this->getDotDivide($request);
	// 	$dates = array_map(function ($date) {
	// 		return Carbon::create($date);
	// 	}, $request["date_range"]);
	// 	$total =  Lead::whereDate("created_at", ">=", $dates[0])->whereDate("created_at", "<=", $dates[1])->count();
	// 	$qty =  0;
	// 	$trend = "keep";
	// 	$data = [
	// 		"qty" => $total,
	// 		"trend" => $trend,
	// 		"rows" => ["Até " . $dates[1]->format("d/m/Y") => $total]
	// 	];
	// 	for ($i = 1; $i <= $this->divisor; $i++) {
	// 		$dates[1] = $dates[1]->subDays($i * $qty_divides);
	// 		$new_qty =  Lead::whereDate("created_at", ">=", $dates[0])->whereDate("created_at", "<=", $dates[1])->count();
	// 		$data["rows"]["Até " . $dates[1]->format("d/m/Y")] = $new_qty;
	// 		$trend = $this->getTrend($qty, $new_qty);
	// 		$qty = $new_qty;
	// 	}
	// 	$data["rows"] = array_reverse($data["rows"]);
	// 	return $data;
	// }

	// private function getDotDivide(Request $request)
	// {
	// 	$date_range = $request['date_range'];
	// 	$diff = Carbon::create($date_range[1])->diffInDays($date_range[0]);
	// 	return $diff <= 0 ? 1 : $diff / $this->divisor;
	// }

	// private function getTrend($qty, $new_qty)
	// {
	// 	if ($new_qty > $qty) return "up";
	// 	if ($new_qty < $qty) return "down";
	// 	return "keep";
	// }
}