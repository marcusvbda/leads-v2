<?php

namespace Tests;

use Tests\TestCase;
use Auth;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Request;

class DefaultTest extends TestCase
{
	use DatabaseTransactions;
	public $user = null;

	protected function login($user_id = 2)
	{
		$this->user = User::find($user_id);
		Auth::login($this->user);
	}

	protected function makeRequest($data = [])
	{
		$request = new Request();
		$request->merge($data);
		return $request;
	}
}