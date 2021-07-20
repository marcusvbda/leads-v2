<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;

class PoloController extends Controller
{
	public function changeLogged(Request $request)
	{
		$user = Auth::user();
		$user->polo_id = $request["id"];
		$user->save();
		return ['success' => true];
	}
}