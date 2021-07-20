<?php

namespace App\Http\Resources;

use marcusvbda\vstack\Resource;
use Auth;

class Convites extends Resource
{
	public $model = \App\Http\Models\UserInvite::class;

	public function viewList()
	{
		return false;
	}

	public function canCreate()
	{
		return false;
	}

	public function canUpdate()
	{
		return false;
	}

	public function canView()
	{
		return false;
	}

	public function canDelete()
	{
		return false;
	}


	public function table()
	{
		$user = Auth::user();
		$columns = [];
		$columns["email"] = ["label" => "Email", "sortable" => false];
		if ($user->hasRole(["super-admin"])) $columns["tenant->name"] = ["label" => "Tenant", "sortable" => false];
		$columns["f_route"] = ["label" => "", "sortable" => false];
		$columns["f_time"] = ["label" => "", "sortable" => false];
		$columns["cancel_invite"] = ["label" => "", "sortable" => false];
		$columns["resend_route"] = ["label" => "", "sortable" => false];
		return $columns;
	}
}