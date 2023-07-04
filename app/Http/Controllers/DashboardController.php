<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;

class DashboardController extends Controller
{
	private $time_to_cache = 2; // minutos
	public function index()
	{
		return view('admin.dashboard');
	}

	protected function getCacheTime()
	{
		return 60 * $this->time_to_cache;
	}

	protected function newLeadsQty(Request $request)
	{
		$user = Auth::user();
		$today = $request["today"];
		return $user->tenant->storeRemember(__CLASS__ . "@" . __FUNCTION__, $this->getCacheTime(), function () use ($user, $today) {
			$data = DB::select("select * from leads where deleted_at is null and tenant_id = :tenant_id and DATE(created_at) = DATE(:created_at)", [
				"tenant_id" => $user->tenant_id,
				"created_at" => $today
			]);
			return  @$data[0]->qty ?? 0;
		});
	}

	public function getData($action, Request $request)
	{
		return $this->{$action}($request);
	}

	protected function polosQty()
	{
		$user = Auth::user();
		return  $user->tenant->storeRemember(__CLASS__ . "@" . __FUNCTION__, $this->getCacheTime(), function () use ($user) {
			$data = DB::select("select count(*) as qty from polos where deleted_at is null and tenant_id = :tenant_id", [
				"tenant_id" => $user->tenant_id,
			]);
			return  @$data[0]->qty ?? 0;
		});
	}

	protected function departmentesQty()
	{
		$user = Auth::user();
		return $user->tenant->storeRemember(__CLASS__ . "@" . __FUNCTION__, $this->getCacheTime(), function () use ($user) {
			$data = DB::select("select count(*) as qty from departments where deleted_at is null and tenant_id = :tenant_id", [
				"tenant_id" => $user->tenant_id
			]);
			return  @$data[0]->qty ?? 0;
		});
	}

	protected function usersQty()
	{
		$user = Auth::user();
		return $user->tenant->storeRemember(__CLASS__ . "@" . __FUNCTION__, $this->getCacheTime(), function () use ($user) {
			$data = DB::select("select count(*) as qty from users where deleted_at is null and tenant_id = :tenant_id", ["tenant_id" => $user->tenant_id]);
			return @$data[0]->qty ?? 0;
		});
	}
}
