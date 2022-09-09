<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mail_integrators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("name");
            $table->string("provider");
            $table->string("email");
            $table->string("from_name");
            $table->string("hash_password");
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants');
            $table->unsignedBigInteger('polo_id');
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
        Schema::dropIfExists('mail_integrators');
    }
};
