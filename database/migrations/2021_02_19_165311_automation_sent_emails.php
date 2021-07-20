<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AutomationSentEmails extends Migration
{
	public function up()
	{
		Schema::create('automation_sent_emails', function (Blueprint $table) {
			$table->charset = 'utf8mb4';
			$table->collation = 'utf8mb4_unicode_ci';
			$table->engine = 'InnoDB';
			$table->bigIncrements('id');
			$table->jsonb('email_content');
			$table->unsignedBigInteger('custom_automation_id')->nullable();
			$table->foreign('custom_automation_id')
				->references('id')
				->on('custom_automations');
			$table->unsignedBigInteger('lead_id')->nullable();
			$table->foreign('lead_id')
				->references('id')
				->on('leads');
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
		Schema::dropIfExists('automation_sent_emails');
	}
}