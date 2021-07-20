<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use marcusvbda\vstack\Services\SendMail;
use marcusvbda\vstack\Services\Messages;
use App\User;
use Auth;

class ForgotPasswordController extends Controller
{
	public function index()
	{
		Auth::logout();
		return view("admin.auth.forgot_password");
	}

	private function sendRecoveryEmail($user)
	{
		Auth::logout();
		$user->recovery_token = md5($user->created_at . "_" . $user->id);
		$link = "/esqueci-a-senha/" . $user->recovery_token;
		$appName = config("app.name");
		$user->save();
		$html = view("mail.forget_password", compact("user", "link", "appName"))->render();
		SendMail::to($user->email, "Renove sua senha", $html);
	}

	public function resetPassword(Request $request)
	{
		Auth::logout();
		$this->validate($request, ['email'    => 'required|email|exists:users']);
		$user = User::where("email", $request["email"])->first();
		if (!$user) {
			Messages::send("danger", "Email não encontrado");
			return ["success" => false, "route" => "/esqueci-a-senha"];
		}
		$this->sendRecoveryEmail($user);
		Messages::send("success", "Um email com o processidemento de renovação de senha foi enviado, verifique seu inbox");
		return ["success" => true, "route" => "/admin"];
	}

	public function renewPassword($token)
	{
		Auth::logout();
		$user = User::where("recovery_token", $token)->firstOrFail();
		return view("admin.auth.renew_password", compact("token"));
	}

	public function setPassword($token, Request $request)
	{
		Auth::logout();
		$this->validate($request, [
			'password' => 'required|confirmed',
			'password_confirmation' => 'required',
		]);
		$user = User::where("recovery_token", $token)->firstOrFail();
		$user->password = $request["password"];
		$user->recovery_token = null;
		$user->save();
		Messages::send("success", "Sua senha foi alterada com sucesso !!");
		return ["success" => true, "route" => "/admin"];
	}
}