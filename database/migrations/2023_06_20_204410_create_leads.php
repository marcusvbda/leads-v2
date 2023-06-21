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
			$table->string('name');
			$table->string('doc_number')->nullable();
			$table->string('phone')->nullable();
			$table->string('secondary_phone')->nullable();
			$table->string('email')->nullable();
			$table->string('profession')->nullable();
			$table->string('zipcode')->nullable();
			$table->string('district')->nullable();
			$table->date('birthdate')->nullable();
			$table->string('country');
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('number')->nullable();
			$table->string('complementary')->nullable();
			$table->longText('obs')->nullable();
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
			$table->timestamp("finished_at")->nullable();
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
