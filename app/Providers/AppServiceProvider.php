<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App;

use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use App\Channels\DatabaseChannel;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		// 
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */

	public function boot()
	{
		Schema::defaultStringLength(191);
		Paginator::useBootstrap();
		$this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel());

		$this->loadMigrationsFrom(base_path('database/migrations'), 'migrations');
	}
}
