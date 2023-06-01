<?php

namespace Tests;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefaultTest extends TestCase
{
	use DatabaseTransactions;
	public $user = null;
}