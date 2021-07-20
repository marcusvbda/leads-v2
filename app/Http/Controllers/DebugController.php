<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Models\{UserNotification, Polo};
use Twilio\Rest\Client;

class DebugController extends Controller
{
	// public function testUserNotification()
	// {
	// 	$user = User::find(1);
	// 	UserNotification::create([
	// 		"user_id" => $user->id,
	// 		"data" => [
	// 			"message" => "Lorem ipsum dolor sit amet, <b>consectetur adipiscing elit</b>. Morbi nisi elit, condimentum et velit eget, <b>finibus condimentum eros</b>. Proin viverra aliquet purus, id sodales erat facilisis varius. Donec quis tortor in augue mattis malesuada quis nec augue. Lorem ipsum dolor sit amet, consectetur adipiscing eli",
	// 			"icon" => "el-icon-picture-outline-round",
	// 			"url" => "https://www.google.com"
	// 		]
	// 	]);

	// 	return ['success' => true];
	// }

	// public function testPoloNotification()
	// {
	// 	$polo = Polo::find(8);
	// 	for ($y = 0; $y < 20; $y++) {
	// 		foreach ($polo->getAllPossibleUsers() as  $user) {
	// 			UserNotification::create([
	// 				"user_id" => $user->id,
	// 				"data" => [
	// 					"message" => "Lorem ipsum dolor sit amet, <b>consectetur adipiscing elit</b>. Morbi nisi elit, condimentum et velit eget, <b>finibus condimentum eros</b>",
	// 					"icon" => "el-icon-s-ticket",
	// 					"url" => "https://www.google.com"
	// 				]
	// 			]);
	// 		}
	// 	}
	// 	return ['success' => true];
	// }
}