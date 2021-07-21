<?php

namespace App\Http\Controllers;

use Auth;

class AttendanceController extends Controller
{
	public function index()
	{
		if (!hasPermissionTo('edit-leads')) abort(403);
		$logged_user = Auth::user()->load("department");
		return view("admin.leads.attendance", compact("logged_user"));
	}
}