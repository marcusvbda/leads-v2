<?php

namespace Tests\Unit\Models;

use Tests\DefaultTest;
use App\User;
use Faker\Factory;

class UserTest extends DefaultTest
{
	private $created = null;
	public function testCreate()
	{
		$this->login(2);
		$faker = Factory::create();
		$this->created =  new User();
		$this->created->name = $faker->name;
		$this->created->email = $faker->email;
		$this->created->password = $faker->password;
		$this->created->tenant_id = $this->user->tenant_id;
		$this->created->save();
		$this->created->assignRole("admin");
		$this->assertTrue($this->created->id > 0);
	}

	public function testCode()
	{
		if (!$this->created) $this->testCreate();
		$this->assertTrue(strlen($this->created->code) > 0);
	}

	public function testRoleName()
	{
		if (!$this->created) $this->testCreate();
		$this->assertTrue(strlen($this->created->roleName) > 0);
	}

	public function testTenantName()
	{
		if (!$this->created) $this->testCreate();
		$this->assertTrue(strlen(@$this->created->tenant->name) > 0);
	}
}