<?php

namespace App\Http\Controllers;

use App\Http\Models\Webhook;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
	public function handler($token, Request $request)
	{
		$webhook = Webhook::where('token', $token)->firstOrFail();
		$webhook->requests()->create(['content' => $request->all()]);
		return response('OK');
	}
}