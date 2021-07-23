<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeads extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leads', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->jsonb('data');
			$table->unsignedBigInteger('status_id');
			$table->foreign('status_id')
				->references('id')
				->on('statuses');
			$table->unsignedBigInteger('webhook_id')->nullable();
			$table->foreign('webhook_id')
				->references('id')
				->on('webhooks');
			$table->unsignedBigInteger('webhook_request_id')->nullable();
			$table->foreign('webhook_request_id')
				->references('id')
				->on('webhook_requests');
			$table->unsignedBigInteger('responsible_id')->nullable();
			$table->foreign('responsible_id')
				->references('id')
				->on('users');
			$table->unsignedBigInteger('department_id')->nullable();
			$table->foreign('department_id')
				->references('id')
				->on('departments');
			$table->unsignedBigInteger('user_id')->nullable();
			$table->foreign('user_id')
				->references('id')
				->on('users');
			$table->unsignedBigInteger('tenant_id')->nullable();
			$table->foreign('tenant_id')
				->references('id')
				->on('tenants');
			$table->unsignedBigInteger('polo_id')->nullable();
			$table->foreign('polo_id')
				->references('id')
				->on('polos');
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
		Schema::dropIfExists('leads');
	}
}