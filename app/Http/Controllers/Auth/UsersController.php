<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Models\Role;
use Illuminate\Http\Request;
use marcusvbda\vstack\Services\Messages;
use App\Http\Models\UserInvite;
use marcusvbda\vstack\Services\SendMail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\User;
use Auth;

class UsersController extends Controller
{
	public function resendInvite($id, Request $request)
	{
		$invite = UserInvite::findOrFail($id);
		$this->inviteEmail($invite);
		Messages::send("success", "Convite de usuário reenviado para o email <b>" . $invite->email . "</b>");
		return redirect(redirect()->back()->getTargetUrl());
	}

	public function cancelInvite($id, Request $request)
	{
		$invite = UserInvite::findOrFail($id);
		$invite->delete();
		Messages::send("success", "Convite do usuário <b>" . $invite->email . "</b> cancelado com sucesso");
		return redirect(redirect()->back()->getTargetUrl());
	}

	public function inviteEmail($invite)
	{
		$route = $invite->route;
		$email = $invite->email;
		$html = view("mail.invite_user", compact("route"))->render();
		SendMail::to($email, "Convite para Ezcore Leads", $html);
		$invite->updated_at = Carbon::now();
		$invite->save(); //altera updated_at
	}

	public function userCreate($tenant_id, $invite_md5, Request $request)
	{
		Auth::logout();
		$invite = UserInvite::where("md5", $invite_md5)->where("tenant_id", $tenant_id)->firstOrFail();
		return view("admin.users.accept_invite", compact("invite"));
	}

	public function userConfirm($tenant_id, $invite_md5, Request $request)
	{
		Auth::logout();
		$invite = UserInvite::where("md5", $invite_md5)->where("tenant_id", $tenant_id)->where("email", $request["email"])->firstOrFail();

		$this->validate($request, [
			'name' => 'required',
			'email'    =>  ['required', 'email', Rule::unique('users')->whereNull('deleted_at')],
			'password' => 'required|confirmed',
			'password_confirmation' => 'required',
		]);
		$data = $request->except(["_token", "password_confirmation"]);
		$user = new User();
		$user->tenant_id = @$invite->data->tenant_id ?? $invite->tenant_id;
		$user->email = $request["email"];
		$user->name = $request["name"];
		$user->department_id = @$invite->data->department_id;
		$user->password = $data['password'];
		$user->save();
		if (@$invite->data->polo_ids) {
			$user->polos()->sync($invite->data->polo_ids);
		}
		$this->deleteOldInvitesForThisEmail($request["email"]);
		Messages::send("success", "Convite aceito e cadastro efetuado com sucesso, você já pode acessar o sistema");
		$this->deleteOldInvitesForThisEmail($request["email"]);
		return ["success" => true, "route" => '/admin'];
	}

	private function deleteOldInvitesForThisEmail($email)
	{
		UserInvite::where("email", $email)->delete();
	}
}
