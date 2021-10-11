<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotification extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_notifications', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->unsignedBigInteger('polo_id')->nullable();
			$table->foreign('polo_id')
				->references('id')
				->on('polos');
			$table->unsignedBigInteger('tenant_id')->nullable();
			$table->foreign('tenant_id')
				->references('id')
				->on('tenants');
			$table->unsignedBigInteger('user_id')->nullable();
			$table->foreign('user_id')
				->references('id')
				->on('users');
			$table->jsonb("data");
			$table->softDeletes();
			$table->timestamps();
			$table->timestamp("read_at")->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user_notifications');
	}
}