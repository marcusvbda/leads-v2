<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use marcusvbda\vstack\Services\Messages;
use ResourcesHelpers;
use App\Http\Models\{Role, UserInvite, Tenant};
use marcusvbda\vstack\Services\SendMail;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use App\User;
use Auth;
use DB;

class UsersController extends Controller
{
	public function create()
	{
		$resource = ResourcesHelpers::find("usuarios");
		if (!$resource->canCreate()) abort(401);
		$roles = Role::where("id", ">", 0);
		$user = Auth::user();
		if (!$user->hasRole(["super-admin"])) $roles = $roles->where("name", "super-admin");
		$roles = $roles->get();
		return view("admin.users.create", compact("resource", "roles"));
	}

	public function sendInvite(Request $request)
	{
		$this->validate($request, [
			'email' => ['required', 'email', function ($attribute, $value, $fail) use ($request) {
				if (DB::table("users")->whereEmail($request["email"])->count() > 0) $fail("Este E-mail já está utilizado por outro usuário !!");
			}],
			'role_id' => 'required',
			'polos' => 'required'
		]);
		$email = $request["email"];
		$invite = UserInvite::create([
			"email" => $request["email"],
			"data" => $request->except(["email"]),
			"tenant_id" => Auth::user()->tenant_id
		]);
		$this->inviteEmail($invite);
		Messages::send("success", "Convite de usuário enviado para o email <b>$email</b>");
		return ["success" => true];
	}

	public function resendInvite($code, Request $request)
	{
		$invite = UserInvite::findByCodeOrFail($code);
		$this->inviteEmail($invite);
		Messages::send("success", "Convite de usuário reenviado para o email <b>" . $invite->email . "</b>");
		return redirect(redirect()->back()->getTargetUrl());
	}

	public function cancelInvite($code, Request $request)
	{
		$invite = UserInvite::findByCodeOrFail($code);
		$invite->delete();
		Messages::send("success", "Convite do usuário <b>" . $invite->email . "</b> cancelado com sucesso");
		return redirect(redirect()->back()->getTargetUrl());
	}

	private function inviteEmail($invite)
	{
		$route = $invite->route;
		$email = $invite->email;
		$name = $invite->tenant->name;
		$html = view("mail.invite_user", compact("route", "name"))->render();
		SendMail::to($email, "Você foi convidado para fazer parte da loja $name", $html);
		$invite->updated_at = Carbon::now();
		$invite->save();
	}

	public function userCreate($tenant_id, $invite_md5, Request $request)
	{
		Auth::logout();
		$invite = UserInvite::where("md5", $invite_md5)->where("tenant_id", $tenant_id)->where("email", $request["email"])->firstOrFail();
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
		$tenant = Tenant::findOrFail($invite->tenant_id);
		$user = $tenant->users()->create([
			"name" => $request["name"],
			"email" => $request["email"],
			"password" => $request["password"],
		]);
		$user = new User();
		$user->name =  $request["name"];
		$user->email =  $request["email"];
		$user->password =  $request["password"];
		$user->tenant_id = $tenant->id;
		$user->save();

		$role = Role::findOrFail($invite->data->role_id);
		$user->assignRole($role->name);
		$user->polos()->sync($invite->data->polos);

		Messages::send("success", "Convite aceito e cadastro efetuado com sucesso, você já pode acessar o sistema");
		$this->deleteOldInvitesForThisEmail($request["email"]);
		return ["success" => true, "route" => '/admin'];
	}

	private function deleteOldInvitesForThisEmail($email)
	{
		UserInvite::where("email", $email)->delete();
	}

	private function validationOwnEditing($resource, $id)
	{
		$logged = Auth::user();
		if ($logged->id != $id) {
			if (!$resource->canUpdate()) abort(401);
		}
		return User::findOrFail($id);
	}

	public function edit($id)
	{
		$resource = ResourcesHelpers::find("usuarios");
		$user = $this->validationOwnEditing($resource, $id);
		$logged = Auth::user();
		if ($user->id != $id) {
			$resource = ResourcesHelpers::find("usuarios");
			if (!$resource->canUpdate()) abort(401);
		}
		$user = User::findOrFail($id);
		return view("admin.users.edit", compact("resource", "user"));
	}

	public function profileEdit(Request $request)
	{
		$resource = ResourcesHelpers::find("usuarios");
		$user = $this->validationOwnEditing($resource, $request["id"]);
		$data = $request->all();
		$this->validate($request, [
			'name' => 'required',
			'password'  =>  [function ($attribute, $value, $fail) use ($data) {
				if ($data["change_password"]) {
					if (!$data["password_confirm"] || !$data["password_confirm"])  $fail("Senha é obrigatória");
					if ($value != $data["password_confirm"]) $fail("Senha não confere");
				}
			}]
		]);
		$user->name = $data["name"];
		if (@$data["change_password"]) $user->password = $data["password"];
		$user->save();
		if (Auth::user()->hasRole(['admin', 'super-admin'])) {
			$user->syncRoles(Role::findOrFail($data["role_id"])->name);
			$user->polos()->sync($data["polos"]);
		}
		Messages::send("success", "Usuário editado com sucesso");
		return ["success" => true];
	}
}