<?php

use App\Http\Models\Module;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('modules');
        Schema::create('modules', function (Blueprint $table) {
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug');
            $table->boolean('new_badge')->default(true);
            $table->jsonb('polo_ids')->nullabe();
            $table->unsignedBigInteger('tenant_id');
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants');
            $table->softDeletes();
            $table->timestamps();
        });
        $this->createModules();
    }

    private function createModules()
    {
        Module::create([
            "name" => "Integrador de Email",
            "slug" => "email-integrator",
            "tenant_id" => 1,
            "new_badge" => true,
            "polo_ids" => []
        ]);

        Module::create([
            "name" => "Whatsapp Sender",
            "slug" => "whatsapp",
            "new_badge" => true,
            "tenant_id" => 1,
            "polo_ids" => []
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
