<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantStore extends Migration
{
    public function up()
    {
        Schema::create('tenant_stores', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string("type");
            $table->jsonb("data");
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tenant_stores');
    }
}
