<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use marcusvbda\vstack\Services\Messages;

class RatingController extends Controller
{

	public function index(Request $request)
	{
		if (!hasPermissionTo('config-rating-behavior')) abort(403);
		$tenant = Auth::user()->tenant;
		$default = (array) $tenant->default_rating_rules;
		$rating_rules = array_merge($default, (array)@$tenant->data->rating_rules ?? $default);
		return view("admin.rating_rules.config", compact('rating_rules'));
	}

	public function store(Request $request)
	{
		if (!hasPermissionTo('config-rating-behavior')) abort(403);
		$tenant = Auth::user()->tenant;
		$_data = $tenant->data;
		$_data->rating_rules = $request->all();
		$tenant->data = $_data;
		Messages::send("success", "Regra de classificação alterada");
		return ["success" => $tenant->save()];
	}
}