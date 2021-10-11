<?php

namespace App\Http\Controllers;

use App\Http\Models\UserNotification;
use Auth;
use Carbon\Carbon;

class NotificationsController extends Controller
{
	public function index()
	{
		$user = Auth::user();
		$qty = $user->userNotifications()->isNew()->count();
		$this->setReadAtNotifications($user);
		return view('admin.notifications.index', compact("qty", "user"));
	}

	private function setReadAtNotifications($user)
	{
		$user->userNotifications()->isNew()->update(["read_at" => Carbon::now()]);
		$user->tenant->tenantNotifications()->isNew()->update(["read_at" => Carbon::now()]);
		$user->polo->poloNotifications()->isNew()->update(["read_at" => Carbon::now()]);
	}

	public function getQty()
	{
		$user = Auth::user();
		$qty = $user->getQyNewNotifications();
		$qty += $user->tenant->getQyNewNotifications();
		$qty += $user->polo->getQyNewNotifications();
		return ['qty' => $qty];
	}

	public function paginated()
	{
		$notifications = UserNotification::where(function ($q) {
			$user = Auth::user();
			return $q->where("user_id", $user->id)
				->orWhere("tenant_id", $user->tenant_id)
				->orWhereIn("polo_id", $user->polos()->pluck("id")->toArray());
		})->orderBy("id", "desc")->paginate(10);
		return $notifications;
	}
}