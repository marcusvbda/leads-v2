<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->string('name');
			$table->string('email');
			$table->string('password');
			$table->string('role')->default('user');
			$table->jsonb('data')->nullable();
			$table->string('recovery_token')->nullable();
			$table->unsignedBigInteger('department_id')->nullable();
			$table->foreign('department_id')
				->references('id')
				->on('departments')
				->onDelete('restrict');
			$table->unsignedBigInteger('polo_id')->nullable();
			$table->foreign('polo_id')
				->references('id')
				->on('polos')
				->onDelete('restrict');
			$table->unsignedBigInteger('tenant_id');
			$table->foreign('tenant_id')
				->references('id')
				->on('tenants')
				->onDelete('restrict');
			$table->datetime('logged_at')->nullable();
			$table->datetime('last_logged_at')->nullable();
			$table->rememberToken();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('users');
	}
}
