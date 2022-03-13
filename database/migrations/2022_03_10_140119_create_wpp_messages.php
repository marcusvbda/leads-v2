<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWppMessages extends Migration
{

    public function up()
    {
        Schema::create('wpp_messages', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->jsonb('data')->nullable();
            $table->string('status')->default("waiting");
            $table->unsignedBigInteger('wpp_session_id')->nullable();
            $table->foreign('wpp_session_id')
                ->references('id')
                ->on('wpp_sessions');
            $table->unsignedBigInteger('polo_id')->nullable();
            $table->foreign('polo_id')
                ->references('id')
                ->on('polos');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants');
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
        Schema::dropIfExists('wpp_messages');
    }
}
