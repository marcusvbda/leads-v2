<?php

use Illuminate\Database\Seeder;
use App\Http\Models\{
	Permission,
};

class G_LandPagesPermissionSeeder extends Seeder
{
	public function run()
	{
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->createPermissions();
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('COMMIT;');
	}

	private function createPermissions()
	{
		DB::table("permissions")->where("group", "Landing Pages")->delete();
		Cache::flush('spatie.permission.cache');
		Permission::create(["group" => "Landing Pages", "name" => "viewlist-landing-pages", "description" => "Ver Listagem de Landing Pages"]);
		Permission::create(["group" => "Landing Pages", "name" => "create-landing-pages", "description" => "Cadastrar Landing Pages"]);
		Permission::create(["group" => "Landing Pages", "name" => "edit-landing-pages", "description" => "Editar Landing Pages"]);
		Permission::create(["group" => "Landing Pages", "name" => "destroy-landing-pages", "description" => "Excluir Landing Pages"]);
	}
}
