<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\DatabaseNotification as BaseNotification;
use App\Channels\DatabaseChannel;
use Illuminate\Support\Facades\Blade;

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
		$this->app->instance(IlluminateDatabaseChannel::class, new DatabaseChannel());
		// $this->app->instance(BaseNotification::class, new ExampleNotification());

		$this->loadMigrationsFrom(base_path('database/migrations'), 'migrations');
	}
}