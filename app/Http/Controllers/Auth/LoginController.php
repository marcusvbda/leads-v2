<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\User;
use Carbon\Carbon;

class LoginController extends Controller
{
	public function index()
	{
		Auth::logout();
		return view("admin.auth.login");
	}

	public function signin(Request $request)
	{
		Auth::logout();
		$this->validate($request, [
			'email'    => 'required|email',
			'password' => 'required'
		]);
		$credentials = $request->only('email', 'password');
		if (User::where("email", $credentials["email"])->count() > 0) {
			if (Auth::attempt($credentials, (@$request['remember'] ? true : false))) {
				$user = Auth::user();
				$qty_polos = $user->polos()->count();
				Auth::logout();
				if ($qty_polos <= 0) {
					return ["success" => false, "message" => "Nenhum polo vinculado a este usuário"];
				}
				return ["success" => true, "polos" => $user->polos()->select(["id", "name"])->orderBy("name", "desc")->get()];
			}
		}
		return ["success" => false, "message" => "Credenciais inválidas"];
	}

	public function completeLogin(Request $request)
	{
		$credentials = $request->only('email', 'password');
		if (User::where("email", $credentials["email"])->count() > 0) {
			if (Auth::attempt($credentials, (@$request['remember'] ? true : false))) {
				$user = Auth::user();
				$user->polo_id = $request["polo_id"];
				$now = Carbon::now();
				$user->last_logged_at = $user->logged_at;
				$user->logged_at = $now;
				$user->save();
				$user->tenant->clearStores();
				return ["success" => true, "route" => '/admin'];
			}
		}
		return ["success" => false, "message" => "Credenciais inválidas"];
	}
}
