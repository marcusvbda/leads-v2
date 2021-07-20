<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		Auth::logout();
		DB::statement('SET AUTOCOMMIT=0;');
		DB::statement('SET FOREIGN_KEY_CHECKS=0;');
		$this->call($this->getAllSeeder());
		DB::statement('COMMIT;');
		DB::statement('SET AUTOCOMMIT=1;');
		DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		DB::statement('COMMIT;');
	}

	private function getAllSeeder()
	{
		$seeders = glob(database_path('/seeds/*.php'));
		$_seeders = [];
		foreach ($seeders as $file) {
			require_once $file;
			$classString = basename($file, '.php');
			if ($classString != "DatabaseSeeder") $_seeders[] = get_class(new $classString);
		}
		return $_seeders;
	}
}