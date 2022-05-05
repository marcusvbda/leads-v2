<?php

namespace App\Http\Controllers;

use App\Http\Models\Status;
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

	private function getDateRange($index)
	{
		return @(new DatesController)->getRanges()[$index];
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

	private function makeParameters(Request $request)
	{
		$filter_date = $this->getDateRange($request["selected_range"]);
		$polos_ids = implode(",", @$request["polo_ids"]);
		return [
			"tenant_id" => Auth::user()->tenant_id,
			"start_date" => $filter_date[0],
			"end_date" => $filter_date[1],
			"polo_ids" => @$polos_ids ? $polos_ids : "-1"
		];
	}

	protected function getLeadsData(Request $request)
	{
		$parameters = $this->makeParameters($request);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select count(*) as qty from leads where deleted_at is null and tenant_id = :tenant_id and 
			( DATE(created_at) >= DATE(:start_date) and DATE(created_at) <= DATE(:end_date))
			and polo_id in ($polos_ids)",
				$parameters
			);
			return @$data[0]->qty ?? 0;
		});
	}

	protected function getLeadFinishedData(Request $request)
	{
		$finished = Status::value("finished")->id;
		$parameters = array_merge($this->makeParameters($request), ["finished_status_id" => $finished]);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select count(*) as qty from leads where deleted_at is null and tenant_id = :tenant_id and 
			( DATE(finished_at) >= DATE(:start_date) and DATE(finished_at) <= DATE(:end_date))
			and polo_id in ($polos_ids)
			and status_id = :finished_status_id",
				$parameters
			);
			return @$data[0]->qty ?? 0;
		});
	}

	protected function getRankingDepartments(Request $request)
	{
		$finished = Status::value("finished")->id;
		$parameters = array_merge($this->makeParameters($request), ["finished_status_id" => $finished]);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select ifnull(departments.name,'Sem departamento') as department,count(*) as qty FROM 
			leads left join departments on departments.id=leads.department_id where leads.tenant_id = :tenant_id
			and ( DATE(leads.finished_at) >= DATE(:start_date) and DATE(leads.finished_at) <= DATE(:end_date))
			and leads.polo_id in ($polos_ids)
			and leads.status_id = :finished_status_id
			and leads.deleted_at is null
			group by leads.department_id order by qty desc
			limit 10",
				$parameters
			);
			return $data;
		});
	}

	protected function getRankingUsers(Request $request)
	{
		$finished = Status::value("finished")->id;
		$parameters = array_merge($this->makeParameters($request), ["finished_status_id" => $finished]);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select ifnull(users.name,'Sem Responsável') as user,count(*) as qty FROM 
			leads left join users on users.id=leads.responsible_id where leads.tenant_id = :tenant_id
			and ( DATE(leads.finished_at) >= DATE(:start_date) and DATE(leads.finished_at) <= DATE(:end_date))
			and leads.polo_id in ($polos_ids)
			and leads.status_id = :finished_status_id
			and leads.deleted_at is null			
			group by leads.responsible_id order by qty desc
			limit 10",
				$parameters
			);
			return $data;
		});
	}

	protected function getCanceledTax(Request $request)
	{
		$parameters = $this->makeParameters($request);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select if(statuses.value = 'canceled','canceled','other') as status, count(*) as qty 
			from  statuses left join leads  on leads.status_id = statuses.id  
			where leads.tenant_id = :tenant_id
			and ( DATE(leads.created_at) >= DATE(:start_date) and DATE(leads.created_at) <= DATE(:end_date))
			and leads.polo_id in ($polos_ids)
			and leads.deleted_at is null
			group by status
			ORDER BY qty DESC",
				$parameters
			);
			return $data;
		});
	}

	protected function getFinishedTax(Request $request)
	{
		$parameters = $this->makeParameters($request);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select if(statuses.value = 'finished','finished','other') as status, count(*) as qty 
			from statuses left join leads on leads.status_id = statuses.id  
			where leads.tenant_id = :tenant_id
			and ( DATE(leads.created_at) >= DATE(:start_date) and DATE(leads.created_at) <= DATE(:end_date))
			and leads.polo_id in ($polos_ids)
			and leads.deleted_at is null
			group by status
			ORDER BY qty DESC",
				$parameters
			);
			return $data;
		});
	}

	protected function getLeadsPerStatus(Request $request)
	{
		$parameters = $this->makeParameters($request);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select statuses.name as status, count(*) as qty 
			from statuses left join leads on leads.status_id = statuses.id  
			where leads.tenant_id = :tenant_id
			and ( DATE(leads.created_at) >= DATE(:start_date) and DATE(leads.created_at) <= DATE(:end_date))
			and leads.polo_id in ($polos_ids)
			and leads.deleted_at is null
			group by status",
				$parameters
			);
			return $data;
		});
	}

	protected function getLeadPerObjection(Request $request)
	{
		$parameters = $this->makeParameters($request);
		$polos_ids = $parameters["polo_ids"];
		unset($parameters["polo_ids"]);
		$cacheName = __CLASS__ . "@" . __FUNCTION__ . ":" . json_encode($parameters);
		$user = Auth::user();
		return $user->tenant->storeRemember($cacheName, $this->getCacheTime(), function () use ($polos_ids, $parameters) {
			$data = DB::select(
				"select JSON_UNQUOTE(JSON_EXTRACT(data,'$.objection.name')) as objection, count(*) as qty
			FROM leads where JSON_UNQUOTE(JSON_EXTRACT(data,'$.objection.name')) is not null 
			and tenant_id = :tenant_id
			and ( DATE(leads.created_at) >= DATE(:start_date) and DATE(leads.created_at) <= DATE(:end_date))
			and leads.polo_id in ($polos_ids) 
			and leads.deleted_at is null
			group by objection",
				$parameters
			);
			return $data;
		});
	}
}
