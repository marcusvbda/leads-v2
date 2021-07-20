<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebhooksRequest  extends Migration
{
	public function up()
	{
		Schema::create('webhook_requests', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->jsonb('content');
			$table->boolean('approved')->default(false);
			$table->unsignedBigInteger('webhook_id');
			$table->foreign('webhook_id')
				->references('id')
				->on('webhooks');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('webhook_requests');
	}
}