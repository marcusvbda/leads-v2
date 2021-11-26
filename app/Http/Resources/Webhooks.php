<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use marcusvbda\vstack\Fields\{
	Card,
	Text,
	Check,
	CustomComponent
};
use Auth;

class Webhooks extends Resource
{
	public $model = \App\Http\Models\Webhook::class;

	public function globallySearchable()
	{
		return false;
	}

	public function label()
	{
		return "Webhooks";
	}

	public function singularLabel()
	{
		return "Webhook";
	}

	public function icon()
	{
		return "el-icon-finished";
	}

	public function search()
	{
		return ["name"];
	}

	private function isHead()
	{
		return @Auth::user()->polo->head ?? false;
	}

	public function table()
	{
		$columns = [];
		$columns["code"] = ["label" => "#", "sortable_index" => "id"];
		$columns["label"] = ["label" => "Descrição"];
		$columns["url"] = ["label" => "Url", "sortable_index" => "token"];
		return $columns;
	}

	public function canCreate()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"]) && $this->isHead();
	}

	public function canUpdate()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function canDelete()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function canClone()
	{
		return false;
	}

	public function canImport()
	{
		return false;
	}

	public function canExport()
	{
		return false;
	}

	public function canViewList()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function canView()
	{
		return  Auth::user()->hasRole(["super-admin", "admin"])  && $this->isHead();
	}

	public function fields()
	{
		$fields = [
			new Text([
				"label" => "Nome",
				"field" => "name",
				"required" => true,
				"rules" => "max:255"
			]),
			new Text([
				"label" => "Token",
				"field" => "token",
				"required" => true,
				"disabled" => true,
				"default" => md5(uniqid()),
				"rules" => "max:255"
			]),
			new Check([
				"label" => "Habilitado",
				"field" => "enabled",
				"default" => true
			])
		];
		if (in_array(request()->get('page_type'), ["view", "edit"])) {
			$fields[] = new CustomComponent($this->requestsViews());
			$fields[] = new CustomComponent($this->settingsViews());
		}
		$cards = [new Card("Informações Básicas", $fields)];
		return $cards;
	}

	private function settingsViews()
	{
		$webhook = request("content");
		$data = $webhook->settings()->orderBy("id", "desc")->paginate(5, ["*"], 'settings_page', request("settings_page") ?? 1);
		$tenant_id = Auth::user()->tenant_id;
		return view("admin.webhooks.settings", compact("data", "webhook", "tenant_id"));
	}

	private function requestsViews()
	{
		$webhook = request("content");
		$query = $webhook->requests()->orderBy("id", "desc");
		if (request("request_status")) {
			if (request("request_status") != "all") {
				$query = $query->where("approved", request("request_status") == "waiting" ? false : true);
			}
		}

		$query = $query->where("hide", request("visibility") == 'hidden' ? true : false);
		$data = $query->paginate(5, ["*"], 'requests_page', request("requests_page") ?? 1);
		$tenant_id = Auth::user()->tenant_id;
		return view("admin.webhooks.requests", compact("data", "webhook", "tenant_id"));
	}
}
